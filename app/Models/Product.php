<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'category_id',
        'image',
        'discount',
        'discount_ends_at'
    ];

    protected $casts = [
        'discount_ends_at' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function reviewsCount()
    {
        return $this->reviews()->count();
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->discount > 0 && ($this->discount_ends_at === null || $this->discount_ends_at->isFuture())) {
            return $this->price * (1 - $this->discount / 100);
        }
        return $this->price;
    }

    public function hasActiveDiscount()
    {
        return $this->discount > 0 && ($this->discount_ends_at === null || $this->discount_ends_at->isFuture());
    }
}
