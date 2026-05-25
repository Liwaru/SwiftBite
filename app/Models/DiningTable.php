<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiningTable extends Model
{
    protected $table = 'tables';

    protected $primaryKey = 'id_meja';

    protected $fillable = [
        'nama_meja',
        'token',
        'status',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_meja', 'id_meja');
    }

    public function getIdAttribute(): int|string
    {
        return $this->id_meja;
    }

    public function getNameAttribute(): string
    {
        return $this->nama_meja;
    }

    public function getQrTokenAttribute(): string
    {
        return $this->token;
    }

    public function getIsActiveAttribute(): bool
    {
        return true;
    }
}
