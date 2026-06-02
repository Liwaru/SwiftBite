<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageItem extends Model
{
    protected $table = 'package_items';

    protected $primaryKey = 'id_paket_item';

    protected $fillable = [
        'id_paket',
        'id_menu',
        'qty',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'id_paket', 'id_paket');
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'id_menu', 'id_menu');
    }
}
