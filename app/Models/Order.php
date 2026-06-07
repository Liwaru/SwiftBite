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
        'payment_status',
        'midtrans_order_id',
        'qris_url',
        'payment_expires_at',
        'payment_payload',
        'customer_name',
        'notes',
    ];

    protected $casts = [
        'payment_expires_at' => 'datetime',
        'payment_payload' => 'array',
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

    public function getPaymentLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'gopay' => 'GoPay',
            'ovo' => 'OVO',
            'dana' => 'DANA',
            'shopeepay' => 'ShopeePay',
            default => strtoupper((string) $this->payment_method),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses Baker',
            'siap_diantar' => 'Siap Diantar',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }

    public function getFlowStepAttribute(): int
    {
        return match ($this->status) {
            'menunggu' => 1,
            'diproses' => 2,
            'siap_diantar' => 3,
            'menunggu_pembayaran' => 4,
            'selesai' => 5,
            default => 0,
        };
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
