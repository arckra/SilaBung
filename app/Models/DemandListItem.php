<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandListItem extends Model
{
    protected $fillable = [
        'demand_list_id', 'category_id', 'search_term', 'quantity',
        'unit', 'status', 'fulfilled_quantity',
    ];

    protected $casts = [
        'quantity'           => 'integer',
        'fulfilled_quantity' => 'integer',
    ];

    public function demandList()  { return $this->belongsTo(DemandList::class); }
    public function category()    { return $this->belongsTo(Category::class); }
    public function allocations() { return $this->hasMany(DemandAllocation::class); }

    /** Ringkas: "Kardus (≥ 5 pcs dari 2 supplier)". */
    public function summary(): string
    {
        $count = $this->allocations->where('selected', true)->count();
        return $this->category->name
            . ($this->search_term ? " ({$this->search_term})" : '')
            . ' — ' . $this->fulfilled_quantity . '/' . $this->quantity
            . ' ' . $this->unit
            . " dari {$count} supplier";
    }
}