<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $fillable = ['quantity'];
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }
}
