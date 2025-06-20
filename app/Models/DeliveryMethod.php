<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'delivery_time',
        'is_active'
    ];

    public function getNameAttribute($value)
    {
        return __("delivery.{$value}.name");
    }

    public function getDescriptionAttribute($value)
    {
        return __("delivery.{$value}.description");
    }

}
