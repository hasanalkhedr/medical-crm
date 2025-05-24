<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = ['lead_id', 'taxes'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_quote')
            ->withPivot('quantity', 'price');
    }
    public function quoteProducts()
    {
        return $this->hasMany(ProductQuote::class);
    }
    protected function total(): Attribute
    {
        return Attribute::make(
            get: function () {
                $total = 0;

                foreach ($this->quoteProducts as $product) {
                    $total += $product->price * $product->quantity;
                }

                return $total * (1 + (is_numeric($this->taxes) ? $this->taxes : 0) / 100);
            }
        );
    }

    protected function subtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                $subtotal = 0;

                foreach ($this->quoteProducts as $product) {
                    $subtotal += $product->price * $product->quantity;
                }

                return $subtotal;
            }
        );
    }
}
