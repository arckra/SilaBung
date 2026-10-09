<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandList extends Model
{
    protected $fillable = ['customer_id', 'title', 'status', 'note', 'confirmed_at'];

    protected $casts = ['confirmed_at' => 'datetime'];

    public function customer()   { return $this->belongsTo(User::class, 'customer_id'); }
    public function items()      { return $this->hasMany(DemandListItem::class); }

    public function allocations()
    {
        return $this->hasManyThrough(
            DemandAllocation::class,
            DemandListItem::class,
            'demand_list_id',
            'demand_list_item_id'
        );
    }

    /** Daftar supplier unik yang terlibat (hanya yang selected). */
    public function selectedSuppliers()
    {
        return $this->allocations()
            ->where('selected', true)
            ->with('supplier')
            ->get()
            ->pluck('supplier')
            ->unique('id')
            ->values();
    }

    public function isFull(): bool
    {
        return $this->items->every(fn ($i) => $i->status === 'full');
    }
}