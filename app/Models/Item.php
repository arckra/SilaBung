<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'supplier_id',
        'category_id',
        'name',
        'description',
        'quantity',
        'unit',
        'condition',
        'image_path',
        'status',
        'city',
        'address',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'quantity'  => 'integer',
            'latitude'  => 'float',
            'longitude' => 'float',
        ];
    }

    /* ---------- Relasi ---------- */

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function requests()
    {
        return $this->hasMany(ItemRequest::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /* ---------- Scope ---------- */

    /** Hanya barang yang masih tersedia & stoknya > 0. */
    public function scopeAvailable(Builder $q): Builder
    {
        return $q->where('status', 'available')->where('quantity', '>', 0);
    }

    /** Pencarian teks pada nama, deskripsi, kondisi. */
    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        if (! $term) {
            return $q;
        }
        $like = '%' . $term . '%';
        return $q->where(function ($w) use ($like) {
            $w->where('name', 'like', $like)
              ->orWhere('description', 'like', $like)
              ->orWhere('condition', 'like', $like)
              ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like));
        });
    }

    /* ---------- Helper ---------- */

    public function coordinates(): ?array
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }
        return ['lat' => $this->latitude, 'lng' => $this->longitude];
    }

    public function refreshStatus(): void
    {
        if ($this->quantity <= 0) {
            $this->status = 'unavailable';
        } elseif ($this->status === 'unavailable' && $this->quantity > 0) {
            $this->status = 'available';
        }
    }
}