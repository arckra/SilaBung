@extends('layouts.app')

@section('title', 'Pesan')
@section('page-title', 'Pesan')
@section('page-subtitle', auth()->user()->isSupplier() ? 'Percakapan dengan customer' : 'Percakapan dengan supplier')

@section('content')

<div class="bg-white border border-[#E3EAE3] rounded-2xl overflow-hidden
            h-[calc(100vh-140px)] min-h-[500px] grid lg:grid-cols-[340px_1fr]">

    {{-- ============================================
         KOLOM KIRI: DAFTAR (DM + GRUP)
         ============================================ --}}
    <div class="border-r border-[#E3EAE3] flex-col
                {{ ($activeId || $activeGroupId) ? 'hidden lg:flex' : 'flex' }}">

        <div class="px-5 py-4 border-b border-[#E3EAE3]">
            <h2 class="font-extrabold tracking-tight">Pesan</h2>
            <p class="text-[11px] text-[#647164] mt-0.5">
                {{ $conversations->count() + $groups->count() }} percakapan
            </p>
        </div>

        <div class="flex-1 overflow-y-auto">

            {{-- ============ GRUP (di atas) ============ --}}
            @if ($groups->isNotEmpty())
                <div class="px-4 pt-3 pb-1.5">
                    <div class="text-[10px] font-extrabold uppercase tracking-widest text-[#647164]">Grup</div>
                </div>

                @foreach ($groups as $g)
                    @php
                        $unread   = $g->unreadFor(auth()->id());
                        $lastMsg  = $g->messages()->latest()->first();
                        $isActive = ($activeGroupId ?? null) == $g->id;
                    @endphp
                    <a href="{{ route('chat.group', $g) }}"
                       class="flex items-center gap-3 px-4 py-3.5 border-b border-[#F1F5F1] transition
                              {{ $isActive ? 'bg-[#F0FDF4] border-l-2 border-l-[#16A34A]' : 'hover:bg-[#F0FDF4]' }}">
                        <div class="w-11 h-11 rounded-full bg-[#166534] text-white grid place-items-center shrink-0">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <div class="font-bold text-sm truncate">{{ $g->name }}</div>
                                <div class="text-[10px] text-[#647164] shrink-0">
                                    {{ $lastMsg?->created_at?->diffForHumans() }}
                                </div>
                            </div>
                            <div class="text-[10.5px] text-[#166534] font-bold truncate mt-0.5">
                                {{ $g->members->count() }} anggota
                            </div>
                            <div class="text-xs text-[#647164] truncate mt-0.5">
                                {{ $lastMsg?->body ?? 'Belum ada pesan' }}
                            </div>
                        </div>
                        @if ($unread > 0)
                            <span class="ml-1 min-w-[20px] h-5 px-1.5 rounded-full bg-red-500 text-white text-[10px] font-extrabold grid place-items-center shrink-0">
                                {{ $unread }}
                            </span>
                        @endif
                    </a>
                @endforeach
            @endif

            {{-- ============ DM ============ --}}
            @if ($conversations->isNotEmpty())
                <div class="px-4 pt-3 pb-1.5">
                    <div class="text-[10px] font-extrabold uppercase tracking-widest text-[#647164]">Percakapan</div>
                </div>

                @foreach ($conversations as $conv)
                    @php
                        $other    = $conv->otherUser(auth()->id());
                        $last     = $conv->messages()->latest()->first();
                        $unread   = $conv->messages()
                            ->where('sender_id', '!=', auth()->id())
                            ->whereNull('read_at')
                            ->count();
                        $isActive = ($activeId ?? null) == $conv->id;
                    @endphp
                    <a href="{{ route('chat.index', ['c' => $conv->id]) }}"
                       class="flex items-center gap-3 px-4 py-3.5 border-b border-[#F1F5F1] transition
                              {{ $isActive ? 'bg-[#F0FDF4] border-l-2 border-l-[#16A34A]' : 'hover:bg-[#F0FDF4]' }}">
                        <x-avatar :user="$other" :size="44" />
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <div class="font-bold text-sm truncate">{{ $other->name }}</div>
                                <div class="text-[10px] text-[#647164] shrink-0">
                                    {{ $last?->created_at?->diffForHumans() }}
                                </div>
                            </div>
                            @if ($conv->item)
                                <div class="text-[10.5px] text-[#166534] font-bold truncate mt-0.5">
                                    📦 {{ $conv->item->name }}
                                </div>
                            @endif
                            <div class="text-xs text-[#647164] truncate mt-0.5">
                                {{ $last?->body ?? 'Belum ada pesan' }}
                            </div>
                        </div>
                        @if ($unread > 0)
                            <span class="ml-1 min-w-[20px] h-5 px-1.5 rounded-full bg-red-500 text-white text-[10px] font-extrabold grid place-items-center shrink-0">
                                {{ $unread }}
                            </span>
                        @endif
                    </a>
                @endforeach
            @endif

            {{-- Empty --}}
            @if ($conversations->isEmpty() && $groups->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-[#DCFCE7] text-[#166534] grid place-items-center mb-3">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <div class="font-bold text-sm mb-1">Belum ada percakapan</div>
                    <p class="text-xs text-[#647164] leading-relaxed">
                        @if (auth()->user()->isSupplier())
                            Customer akan menghubungimu lewat halaman detail barang.
                        @else
                            Mulai chat dengan supplier dari detail barang, atau konfirmasi wishlist.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================
         KOLOM KANAN: RUANG CHAT (DM ATAU GRUP)
         ============================================ --}}
    <div class="flex-col min-w-0 {{ ($activeId || $activeGroupId) ? 'flex' : 'hidden lg:flex' }}">

        @if ($active)
            {{-- ============ DM ROOM ============ --}}
            @php $other = $active->otherUser(auth()->id()); @endphp
            <div class="flex items-center gap-3 px-4 py-3.5 border-b border-[#E3EAE3] bg-white">
                <a href="{{ route('chat.index') }}"
                   class="lg:hidden w-9 h-9 rounded-lg grid place-items-center text-[#647164] hover:bg-[#F1F5F1] transition shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                </a>
                <x-avatar :user="$other" :size="40" />
                <div class="min-w-0 flex-1">
                    <div class="font-extrabold tracking-tight text-[14px] truncate">{{ $other->name }}</div>
                    @if ($active->item)
                        <div class="text-[10.5px] text-[#647164] truncate">📦 {{ $active->item->name }}</div>
                    @endif
                </div>
            </div>

            <div id="chatMessages" class="flex-1 overflow-y-auto bg-[#F8FAF8] px-4 py-4 space-y-2">
                <div class="text-center text-xs text-[#647164] py-10">Memuat pesan...</div>
            </div>

            <form onsubmit="dmSend(event)" class="border-t border-[#E3EAE3] bg-white p-3 flex gap-2 items-end">
                @csrf
                <textarea id="chatInput" rows="1" maxlength="2000"
                          placeholder="Tulis pesan..."
                          oninput="autoGrow(this)"
                          onkeydown="handleKey(event, 'dm')"
                          class="flex-1 resize-none px-3.5 py-2.5 rounded-xl border border-[#E3EAE3] bg-white text-sm
                                 focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none
                                 max-h-[120px]"></textarea>
                <button type="submit" id="chatSendBtn"
                        class="w-11 h-11 rounded-xl bg-[#16A34A] text-white grid place-items-center
                               hover:bg-[#166534] disabled:opacity-50 transition shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                    </svg>
                </button>
            </form>

        @elseif ($activeGroupId)
            {{-- ============ GROUP ROOM ============ --}}
            @php
                $group = \App\Models\ChatGroup::with('members')->find($activeGroupId);
            @endphp
            <div class="flex items-center gap-3 px-4 py-3.5 border-b border-[#E3EAE3] bg-white">
                <a href="{{ route('chat.index') }}"
                   class="lg:hidden w-9 h-9 rounded-lg grid place-items-center text-[#647164] hover:bg-[#F1F5F1] transition shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                </a>

                {{-- Avatar stack --}}
                <div class="flex -space-x-2 shrink-0">
                    @foreach ($group->members->take(3) as $m)
                        <div class="border-2 border-white rounded-full">
                            <x-avatar :user="$m" :size="34" />
                        </div>
                    @endforeach
                </div>

                <div class="min-w-0 flex-1">
                    <div class="font-extrabold tracking-tight text-[14px] truncate">{{ $group->name }}</div>
                    <div class="text-[10.5px] text-[#647164] truncate">
                        {{ $group->membersLabel(auth()->id()) }}
                    </div>
                </div>

                <button type="button" onclick="toggleMembers()"
                        class="w-9 h-9 rounded-lg grid place-items-center text-[#647164] hover:bg-[#F1F5F1] transition shrink-0"
                        title="Lihat anggota">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                    </svg>
                </button>
            </div>

            {{-- Panel anggota (collapsible) --}}
            <div id="membersPanel" class="hidden border-b border-[#E3EAE3] bg-[#FBFDFB] px-4 py-3">
                <div class="text-[10px] font-extrabold uppercase tracking-widest text-[#647164] mb-2">Anggota Grup</div>
                <div class="space-y-1.5">
                    @foreach ($group->members as $m)
                        <div class="flex items-center gap-2.5">
                            <x-avatar :user="$m" :size="28" />
                            <span class="text-[12px] font-semibold">
                                {{ $m->name }}
                                @if ($m->id === auth()->id()) <span class="text-[#647164] font-medium">(kamu)</span> @endif
                                @if ($m->id === $group->created_by) <span class="text-[#166534] font-bold text-[10px] ml-1">· Pembuat</span> @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="chatMessages" class="flex-1 overflow-y-auto bg-[#F8FAF8] px-4 py-4 space-y-2">
                <div class="text-center text-xs text-[#647164] py-10">Memuat pesan...</div>
            </div>

            <form onsubmit="groupSend(event)" class="border-t border-[#E3EAE3] bg-white p-3 flex gap-2 items-end">
                @csrf
                <textarea id="chatInput" rows="1" maxlength="2000"
                          placeholder="Tulis pesan ke grup..."
                          oninput="autoGrow(this)"
                          onkeydown="handleKey(event, 'group')"
                          class="flex-1 resize-none px-3.5 py-2.5 rounded-xl border border-[#E3EAE3] bg-white text-sm
                                 focus:border-[#16A34A] focus:ring-2 focus:ring-green-500/15 outline-none
                                 max-h-[120px]"></textarea>
                <button type="submit" id="chatSendBtn"
                        class="w-11 h-11 rounded-xl bg-[#16A34A] text-white grid place-items-center
                               hover:bg-[#166534] disabled:opacity-50 transition shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                    </svg>
                </button>
            </form>

        @else
            {{-- Empty state --}}
            <div class="flex-1 grid place-items-center bg-[#F8FAF8]">
                <div class="text-center px-6 max-w-sm">
                    <div class="w-20 h-20 mx-auto rounded-3xl bg-[#DCFCE7] text-[#166534] grid place-items-center mb-4">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-extrabold tracking-tight text-base mb-2">Pilih percakapan</h3>
                    <p class="text-sm text-[#647164] leading-relaxed">
                        Klik salah satu percakapan di samping untuk mulai membaca dan membalas pesan.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

@if ($active)
    // ============ DM MODE ============
    const DM_ID = {{ $active->id }};
    let dmPoller = null;

    async function dmLoad(silent = false) {
        try {
            const res = await fetch(`/chat/${DM_ID}/messages`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            const box = document.getElementById('chatMessages');
            const scroll = !silent || isNearBottom(box);

            box.innerHTML = data.messages.length
                ? renderDmMessages(data.messages)
                : `<div class="text-center text-xs text-[#647164] py-10">Belum ada pesan.<br>Mulai percakapan dengan sopan ya 😊</div>`;

            if (scroll) box.scrollTop = box.scrollHeight;
        } catch (e) { console.error(e); }
    }

    function renderDmMessages(messages) {
        let html = '', lastDate = null;
        messages.forEach(m => {
            if (m.date !== lastDate) {
                html += `<div class="text-center my-3"><span class="text-[10px] font-extrabold tracking-wider text-[#647164] bg-white border border-[#E3EAE3] px-2.5 py-1 rounded-full uppercase">${m.date}</span></div>`;
                lastDate = m.date;
            }
            html += `
                <div class="flex ${m.is_mine ? 'justify-end' : 'justify-start'}">
                    <div class="${m.is_mine ? 'chat-bubble-mine' : 'chat-bubble-theirs'} px-3.5 py-2.5 max-w-[78%] shadow-sm">
                        <div class="text-[13px] leading-relaxed whitespace-pre-wrap break-words">${esc(m.body)}</div>
                        <div class="text-[10px] mt-1 ${m.is_mine ? 'text-white/70' : 'text-[#647164]'} text-right">${m.time}</div>
                    </div>
                </div>`;
        });
        return html;
    }

    async function dmSend(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const btn = document.getElementById('chatSendBtn');
        const body = input.value.trim();
        if (!body) return;
        btn.disabled = true; input.disabled = true;
        try {
            const res = await fetch(`/chat/${DM_ID}/send`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ body }),
            });
            if (!res.ok) throw new Error('Gagal mengirim.');
            input.value = '';
            autoGrow(input);
            await dmLoad();
            const box = document.getElementById('chatMessages');
            box.scrollTop = box.scrollHeight;
        } catch (err) { alert(err.message); }
        finally { btn.disabled = false; input.disabled = false; input.focus(); }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await dmLoad();
        const box = document.getElementById('chatMessages');
        box.scrollTop = box.scrollHeight;
        autoGrow(document.getElementById('chatInput'));
        dmPoller = setInterval(() => dmLoad(true), 3000);
    });
    window.addEventListener('beforeunload', () => dmPoller && clearInterval(dmPoller));
@endif

@if ($activeGroupId)
    // ============ GROUP MODE ============
    const GROUP_ID = {{ $activeGroupId }};
    let groupPoller = null;

    async function groupLoad(silent = false) {
        try {
            const res = await fetch(`/chat/group/${GROUP_ID}/messages`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            const box = document.getElementById('chatMessages');
            const scroll = !silent || isNearBottom(box);

            box.innerHTML = data.messages.length
                ? renderGroupMessages(data.messages)
                : `<div class="text-center text-xs text-[#647164] py-10">Belum ada pesan di grup ini.<br>Mulai koordinasi dengan semua supplier di sini.</div>`;

            if (scroll) box.scrollTop = box.scrollHeight;
        } catch (e) { console.error(e); }
    }

    function renderGroupMessages(messages) {
        let html = '', lastDate = null;
        messages.forEach(m => {
            if (m.date !== lastDate) {
                html += `<div class="text-center my-3"><span class="text-[10px] font-extrabold tracking-wider text-[#647164] bg-white border border-[#E3EAE3] px-2.5 py-1 rounded-full uppercase">${m.date}</span></div>`;
                lastDate = m.date;
            }

            if (m.is_mine) {
                html += `
                    <div class="flex justify-end">
                        <div class="chat-bubble-mine px-3.5 py-2.5 max-w-[78%] shadow-sm">
                            <div class="text-[13px] leading-relaxed whitespace-pre-wrap break-words">${esc(m.body)}</div>
                            <div class="text-[10px] mt-1 text-white/70 text-right">${m.time}</div>
                        </div>
                    </div>`;
            } else {
                html += `
                    <div class="flex justify-start gap-2 items-end">
                        <div class="w-7 h-7 rounded-full bg-[#166534] text-white grid place-items-center text-[9px] font-extrabold shrink-0 mb-1">
                            ${m.avatar_url
                                ? `<img src="${m.avatar_url}" class="w-7 h-7 rounded-full object-cover shrink-0 mb-1" alt="${esc(m.sender)}">`
                                : `<div class="w-7 h-7 rounded-full bg-[#166534] text-white grid place-items-center text-[9px] font-extrabold shrink-0 mb-1">${m.initials}</div>`
                            }
                        </div>
                        <div class="chat-bubble-theirs px-3.5 py-2.5 max-w-[70%] shadow-sm">
                            <div class="text-[10.5px] font-extrabold text-[#166534] mb-1">${esc(m.sender)}</div>
                            <div class="text-[13px] leading-relaxed whitespace-pre-wrap break-words">${esc(m.body)}</div>
                            <div class="text-[10px] mt-1 text-[#647164] text-right">${m.time}</div>
                        </div>
                    </div>`;
            }
        });
        return html;
    }

    async function groupSend(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const btn = document.getElementById('chatSendBtn');
        const body = input.value.trim();
        if (!body) return;
        btn.disabled = true; input.disabled = true;
        try {
            const res = await fetch(`/chat/group/${GROUP_ID}/send`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ body }),
            });
            if (!res.ok) throw new Error('Gagal mengirim.');
            input.value = '';
            autoGrow(input);
            await groupLoad();
            const box = document.getElementById('chatMessages');
            box.scrollTop = box.scrollHeight;
        } catch (err) { alert(err.message); }
        finally { btn.disabled = false; input.disabled = false; input.focus(); }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await groupLoad();
        const box = document.getElementById('chatMessages');
        box.scrollTop = box.scrollHeight;
        autoGrow(document.getElementById('chatInput'));
        groupPoller = setInterval(() => groupLoad(true), 3000);
    });
    window.addEventListener('beforeunload', () => groupPoller && clearInterval(groupPoller));

    window.toggleMembers = function () {
        document.getElementById('membersPanel').classList.toggle('hidden');
    };
@endif

// ============ UTIL ============
function handleKey(e, mode) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('chatSendBtn').click();
    }
}
function autoGrow(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}
function isNearBottom(el) {
    return el.scrollHeight - el.scrollTop - el.clientHeight < 80;
}
function esc(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}
</script>

<style>
.chat-bubble-mine { background: #16A34A; color: #fff; border-radius: 14px 14px 4px 14px; }
.chat-bubble-theirs { background: #fff; color: #172117; border: 1px solid #E3EAE3; border-radius: 14px 14px 14px 4px; }
</style>
@endpush