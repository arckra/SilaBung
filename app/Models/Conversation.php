<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['customer_id', 'supplier_id', 'item_id', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /** User lain (lawan bicara) dari sudut pandang $userId. */
    public function otherUser(int $userId): User
    {
        return $this->customer_id === $userId ? $this->supplier : $this->customer;
    }
}