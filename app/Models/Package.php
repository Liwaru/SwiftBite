<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $table = 'packages';

    protected $primaryKey = 'id_paket';

    protected $fillable = [
        'nama_paket',
        'foto',
        'harga',
        'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class, 'id_paket', 'id_paket');
    }

    public function getNameAttribute(): string
    {
        return $this->nama_paket;
    }

    public function getPriceAttribute(): float
    {
        return (float) $this->harga;
    }
}
