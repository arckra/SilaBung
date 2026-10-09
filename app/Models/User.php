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
    public function conversationsAsCustomer()
    {
        return $this->hasMany(Conversation::class, 'customer_id');
    }

    public function conversationsAsSupplier()
    {
        return $this->hasMany(Conversation::class, 'supplier_id');
    }
}