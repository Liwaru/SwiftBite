<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientIn extends Model
{
    protected $table = 'ingredient_ins';

    protected $fillable = [
        'id_bahan',
        'qty',
        'supplier',
        'note',
        'actor_name',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'id_bahan', 'id_bahan');
    }
}