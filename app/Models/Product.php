<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Product extends Model
{
    protected $fillable = ['name', 'price'];

    public function quotes()
    {
        return $this->belongsToMany(Quote::class, 'product_quote')
            ->withPivot('quantity', 'price');
    }
    protected function price(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => $value / 100,
            set: static fn ($value) => $value * 100,
        );
    }
}
