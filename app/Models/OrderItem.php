<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_details';

    protected $primaryKey = 'id_detail_order';

    protected $fillable = [
        'id_order',
        'id_menu',
        'id_paket',
        'package_name',
        'package_component_type',
        'qty',
        'harga',
        'subtotal',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'id_menu', 'id_menu');
    }

    public function getQuantityAttribute(): int
    {
        return (int) $this->qty;
    }

    public function getMenuNameAttribute(): string
    {
        $name = $this->menuItem?->nama_menu ?? 'Menu';

        if (! empty($this->attributes['package_name'])) {
            $type = ($this->attributes['package_component_type'] ?? '') === 'choice' ? 'pilihan' : 'isi';

            return $this->attributes['package_name'] . ' - ' . $name . ' (' . $type . ')';
        }

        return $name;
    }

    public function getPriceAttribute(): float
    {
        return (float) $this->harga;
    }
}
