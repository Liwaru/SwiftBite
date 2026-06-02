<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientPurchase extends Model
{
    protected $table = 'ingredient_purchases';

    protected $primaryKey = 'id_pembelian_bahan';

    protected $fillable = [
        'id_bahan',
        'qty',
        'satuan',
        'harga_total',
        'note',
        'purchased_at',
    ];

    protected $casts = [
        'qty' => 'float',
        'harga_total' => 'float',
        'purchased_at' => 'datetime',
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'id_bahan', 'id_bahan');
    }
}
