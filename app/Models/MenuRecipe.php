<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRecipe extends Model
{
    protected $table = 'menu_recipes';
    protected $primaryKey = 'id_recipe';

    protected $fillable = [
        'id_menu',
        'id_bahan',
        'qty',
        'unit',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'id_bahan', 'id_bahan');
    }

    public function menu()
    {
        return $this->belongsTo(MenuItem::class, 'id_menu', 'id_menu');
    }
}