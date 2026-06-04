<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageChoice extends Model
{
    protected $table = 'package_choices';

    protected $primaryKey = 'id_paket_choice';

    protected $fillable = [
        'id_paket',
        'category',
        'qty',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'id_paket', 'id_paket');
    }
}
