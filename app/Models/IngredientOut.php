<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientOut extends Model
{
    protected $table = 'ingredient_outs';

    protected $fillable = [
        'id_bahan',
        'qty',
        'reason',
        'note',
        'actor_name',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'id_bahan', 'id_bahan');
    }
}