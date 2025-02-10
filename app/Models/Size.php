<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'type'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sizes')
                    ->withPivot('stock')
                    ->withTimestamps();
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes')
                    ->withPivot('stock')
                    ->withTimestamps();
    }

    public function hasSize($sizeId)
    {
        return $this->sizes->contains('id', $sizeId);
    }

    public function getSizeStock($sizeId)
    {
        $size = $this->sizes->find($sizeId);
        return $size ? $size->pivot->stock : 0;
    }
}
