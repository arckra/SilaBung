<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'role',
        'role_selected_at',
        'city',
        'address',
        'latitude',
        'longitude',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'role_selected_at'  => 'datetime',
            'password'          => 'hashed',
            'latitude'          => 'float',
            'longitude'         => 'float',
        ];
    }
    public function items()
    {
        return $this->hasMany(Item::class, 'supplier_id');
    }
    public function requests()
    {
        return $this->hasMany(ItemRequest::class, 'customer_id');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
    public function isSupplier(): bool
    {
        return $this->role === 'supplier';
    }
    public function hasSelectedRole(): bool
    {
        return ! is_null($this->role);
    }
    public function coordinates(): ?array
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }
        return ['lat' => $this->latitude, 'lng' => $this->longitude];
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar_path) {
            return null;
        }
        return \Storage::disk('public')->url($this->avatar_path);
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(
            collect(explode(' ', trim($this->name)))
                ->filter()
                ->take(2)
                ->map(fn ($w) => mb_substr($w, 0, 1))
                ->implode('')
        );
    }
    public function conversationsAsCustomer()
    {
        return $this->hasMany(Conversation::class, 'customer_id');
    }

    public function conversationsAsSupplier()
    {
        return $this->hasMany(Conversation::class, 'supplier_id');
    }

    public function chatGroups()
    {
        return $this->belongsToMany(ChatGroup::class, 'chat_group_members')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }
}