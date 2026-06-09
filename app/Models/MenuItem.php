<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $table = 'menus';

    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_kategori',
        'nama_menu',
        'barcode',
        'deskripsi',
        'harga',
        'foto',
        'stok',
        'status',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'id_menu', 'id_menu');
    }

    public function categoryModel(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id_kategori');
    }

    public function getIdAttribute(): int|string
    {
        return $this->id_menu;
    }

    public function getNameAttribute(): string
    {
        return $this->nama_menu;
    }

    public function getCategoryAttribute(): string
    {
        return $this->categoryModel?->nama_kategori ?? 'Tanpa kategori';
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->deskripsi;
    }

    public function getPriceAttribute(): float
    {
        return (float) $this->harga;
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'tersedia';
    }
    public function recipes()
{
    return $this->hasMany(MenuRecipe::class, 'id_menu', 'id_menu');
}
}
