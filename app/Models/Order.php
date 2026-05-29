<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $primaryKey = 'id_order';

    protected $fillable = [
        'id_meja',
        'kode_pesanan',
        'total_harga',
        'status',
        'metode_pembayaran',
        'customer_name',
        'notes',
    ];

    public function diningTable(): BelongsTo
    {
        return $this->belongsTo(DiningTable::class, 'id_meja', 'id_meja');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'id_order', 'id_order');
    }

    public function getIdAttribute(): int|string
    {
        return $this->id_order;
    }

    public function getDiningTableIdAttribute(): int|string
    {
        return $this->id_meja;
    }

    public function getCustomerNameAttribute(): ?string
    {
        return $this->attributes['customer_name'] ?? null;
    }

    public function getPaymentMethodAttribute(): string
    {
        return $this->attributes['metode_pembayaran'] ?? $this->attributes['payment_method'] ?? 'cash';
    }

    public function getTotalPriceAttribute(): float
    {
        return (float) $this->total_harga;
    }

    public function getNotesAttribute(): ?string
    {
        return $this->attributes['notes'] ?? null;
    }
}
