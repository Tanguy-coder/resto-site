<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'total' => 'integer',
    ];

    const STATUSES = [
        'received' => 'Reçue',
        'confirmed' => 'Confirmée',
        'preparing' => 'En préparation',
        'ready' => 'Prête',
        'delivered' => 'Terminée',
        'cancelled' => 'Annulée',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getServiceLabelAttribute(): string
    {
        return match ($this->service_type) {
            'sur_place' => 'Sur place',
            'a_emporter' => 'À emporter',
            'livraison' => 'Livraison',
            default => $this->service_type,
        };
    }

    public static function generateNumber(): string
    {
        do {
            $number = str_pad(random_int(100, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('order_number', $number)->whereDate('created_at', today())->exists());

        return $number;
    }
}
