<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    protected $fillable = ['demand_list_id', 'created_by', 'name', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

    public function demandList() { return $this->belongsTo(DemandList::class); }
    public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
    public function messages()   { return $this->hasMany(GroupMessage::class); }

    public function members()
    {
        return $this->belongsToMany(User::class, 'chat_group_members')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    /** Cek user bagian dari grup. */
    public function hasMember(int $userId): bool
    {
        return $this->members()->where('users.id', $userId)->exists();
    }

    /** Jumlah unread pesan untuk user tertentu. */
    public function unreadFor(int $userId): int
    {
        $member = $this->members()->where('users.id', $userId)->first();
        if (! $member) return 0;

        $since = $member->pivot->last_read_at;

        return $this->messages()
            ->when($since, fn ($q) => $q->where('created_at', '>', $since))
            ->where('sender_id', '!=', $userId)
            ->count();
    }

    /** Tandai user sudah baca semua pesan. */
    public function markReadBy(int $userId): void
    {
        $this->members()->updateExistingPivot($userId, ['last_read_at' => now()]);
    }

    /** Label anggota untuk header. */
    public function membersLabel(int $excludeUserId = null): string
    {
        $names = $this->members
            ->when($excludeUserId, fn ($c) => $c->where('id', '!=', $excludeUserId))
            ->pluck('name')
            ->all();

        if (count($names) <= 2) return implode(', ', $names);
        return $names[0] . ', ' . $names[1] . ' +' . (count($names) - 2);
    }
}