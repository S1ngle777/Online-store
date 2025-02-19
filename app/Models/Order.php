<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'total_amount',
        'status',
        'notes',
        'delivery_method_id',
        'payment_method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class)->withDefault([
            'name' => 'Не указан',
            'price' => 0
        ]);
    }

    public function getPaymentMethodTextAttribute()
    {
        return [
            'cash' => 'Наличными при получении',
            'card' => 'Банковской картой онлайн'
        ][$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => [
                'text' => __('messages.pending'),
                'class' => 'bg-yellow-100 text-yellow-800'
            ],
            'processing' => [
                'text' => __('messages.processing'),
                'class' => 'bg-blue-100 text-blue-800'
            ],
            'completed' => [
                'text' => __('messages.completed'),
                'class' => 'bg-green-100 text-green-800'
            ],
            'cancelled' => [
                'text' => __('messages.cancelled'),
                'class' => 'bg-red-100 text-red-800'
            ]
        ];

        return $statuses[$this->status] ?? [
            'text' => $this->status,
            'class' => 'bg-gray-100 text-gray-800'
        ];
    }
}