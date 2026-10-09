<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandAllocation extends Model
{
    protected $fillable = [
        'demand_list_item_id', 'supplier_id', 'item_id',
        'quantity', 'distance_km', 'selected',
    ];

    protected $casts = [
        'quantity'    => 'integer',
        'distance_km' => 'float',
        'selected'    => 'boolean',
    ];

    public function demandListItem() { return $this->belongsTo(DemandListItem::class); }
    public function supplier()       { return $this->belongsTo(User::class, 'supplier_id'); }
    public function item()           { return $this->belongsTo(Item::class); }
}