<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $table = 'ingredients';

    protected $primaryKey = 'id_bahan';

    protected $fillable = [
        'nama_bahan',
        'stok',
        'satuan',
        'stok_minimum',
    ];

    protected $casts = [
        'stok' => 'float',
        'stok_minimum' => 'float',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(IngredientUsage::class, 'id_bahan', 'id_bahan');
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->stok <= 0) {
            return 'Habis';
        }

        if ($this->stok <= $this->stok_minimum) {
            return 'Menipis';
        }

        return 'Aman';
    }

    public function getStatusTypeAttribute(): string
    {
        return match ($this->status_label) {
            'Habis' => 'empty',
            'Menipis' => 'low',
            default => 'safe',
        };
    }
}
