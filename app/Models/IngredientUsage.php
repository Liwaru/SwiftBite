<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientUsage extends Model
{
    protected $table = 'ingredient_usages';

    protected $primaryKey = 'id_penggunaan_bahan';

    protected $fillable = [
        'id_bahan',
        'qty',
        'note',
        'actor_name',
    ];

    protected $casts = [
        'qty' => 'float',
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'id_bahan', 'id_bahan');
    }
}
