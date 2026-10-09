<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Halaman chat: daftar percakapan + ruang chat aktif (kalau ada).
     * URL: /chat atau /chat?c=ID
     */
    public function page(Request $request)
    {
        $user = auth()->user();

        $conversations = Conversation::with(['customer', 'supplier', 'item'])
            ->where(fn ($q) => $user->isCustomer()
                ? $q->where('customer_id', $user->id)
                : $q->where('supplier_id', $user->id))
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->get();

        $activeId = $request->query('c');
        $active   = null;

        if ($activeId) {
            $active = $conversations->firstWhere('id', (int) $activeId);
            if (! $active) {
                return redirect()->route('chat.index');
            }

            // Tandai pesan masuk sebagai sudah dibaca
            Message::where('conversation_id', $active->id)
                ->where('sender_id', '!=', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return view('chat.index', compact('conversations', 'active', 'activeId', 'user'));
    }

    /**
     * JSON: daftar percakapan (untuk polling / sinkronisasi).
     */
    public function list()
    {
        $user = auth()->user();

        $conversations = Conversation::with(['customer', 'supplier', 'item'])
            ->where(fn ($q) => $user->isCustomer()
                ? $q->where('customer_id', $user->id)
                : $q->where('supplier_id', $user->id))
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(function (Conversation $c) use ($user) {
                $other = $c->otherUser($user->id);
                $last  = $c->messages()->latest()->first();
                $unread = $c->messages()
                    ->where('sender_id', '!=', $user->id)
                    ->whereNull('read_at')
                    ->count();

                return [
                    'id'             => $c->id,
                    'other_name'     => $other->name,
                    'other_initials' => strtoupper(substr($other->name, 0, 2)),
                    'item_name'      => $c->item?->name,
                    'last_message'   => $last?->body,
                    'last_at'        => $last?->created_at?->diffForHumans(),
                    'unread'         => $unread,
                ];
            });

        return response()->json($conversations);
    }

    /**
     * JSON: pesan-pesan dalam percakapan + tandai sudah dibaca.
     */
    public function messages(Conversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $userId = auth()->id();

        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn (Message $m) => [
                'id'      => $m->id,
                'body'    => $m->body,
                'is_mine' => $m->sender_id === $userId,
                'time'    => $m->created_at->format('H:i'),
                'date'    => $m->created_at->translatedFormat('d M Y'),
            ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'other_name'      => $conversation->otherUser($userId)->name,
            'other_initials'  => strtoupper(substr($conversation->otherUser($userId)->name, 0, 2)),
            'item_name'       => $conversation->item?->name,
            'messages'        => $messages,
        ]);
    }

    /**
     * Kirim pesan.
     */
    public function send(Request $request, Conversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ], [
            'body.required' => 'Pesan tidak boleh kosong.',
            'body.max'      => 'Pesan terlalu panjang (maks 2000 karakter).',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => auth()->id(),
            'body'            => trim($data['body']),
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'id'      => $message->id,
            'body'    => $message->body,
            'is_mine' => true,
            'time'    => $message->created_at->format('H:i'),
            'date'    => $message->created_at->translatedFormat('d M Y'),
        ]);
    }

    /**
     * Mulai percakapan baru, lalu redirect ke halaman chat.
     * Dipakai dari tombol "Chat Supplier" di detail barang.
     */
    public function start(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'item_id' => ['nullable', 'exists:items,id'],
        ]);

        $me    = auth()->user();
        $other = User::findOrFail($data['user_id']);

        if ($me->id === $other->id) {
            return back()->with('error', 'Tidak bisa memulai chat dengan diri sendiri.');
        }

        if ($me->isCustomer() && $other->isSupplier()) {
            $customerId = $me->id;
            $supplierId = $other->id;
        } elseif ($me->isSupplier() && $other->isCustomer()) {
            $customerId = $other->id;
            $supplierId = $me->id;
        } else {
            return back()->with('error', 'Chat hanya bisa antara customer dan supplier.');
        }

        $conversation = Conversation::firstOrCreate(
            ['customer_id' => $customerId, 'supplier_id' => $supplierId],
            ['item_id' => $data['item_id'] ?? null]
        );

        if (! $conversation->item_id && ! empty($data['item_id'])) {
            $conversation->update(['item_id' => $data['item_id']]);
        }

        return redirect()->route('chat.index', ['c' => $conversation->id]);
    }

    /**
     * Cek bahwa user bagian dari percakapan ini.
     */
    protected function authorizeAccess(Conversation $conversation): void
    {
        $userId = auth()->id();
        abort_unless(
            $conversation->customer_id === $userId || $conversation->supplier_id === $userId,
            403,
            'Kamu tidak memiliki akses ke percakapan ini.'
        );
    }
}