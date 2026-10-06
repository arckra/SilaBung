<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    protected $table = 'item_requests';

    protected $fillable = [
        'item_id',
        'customer_id',
        'quantity',
        'status',
        'note',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity'     => 'integer',
            'responded_at' => 'datetime',
        ];
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /* ---------- Status label ---------- */

    public function statusLabel(): string
    {
        return [
            'pending'   => 'Menunggu',
            'accepted'  => 'Diterima',
            'rejected'  => 'Ditolak',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ][$this->status] ?? $this->status;
    }

    public function statusBadgeClass(): string
    {
        return [
            'pending'   => 'badge-warning',
            'accepted'  => 'badge-success',
            'rejected'  => 'badge-danger',
            'completed' => 'badge-info',
            'cancelled' => 'badge-neutral',
        ][$this->status] ?? 'badge-neutral';
    }

    /* ---------- Guard ---------- */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}