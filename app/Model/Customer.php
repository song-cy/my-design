<?php

namespace App\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{

    use Authenticatable;  //使用trait,相当与将整个trait代码段复制到这个位置
    // protected $keyType = 'string';
    protected $fillable = [
                    'id', 'name', 'password', 'phone',
                    'shop_name', 'dress', 'status', 'town_id'
    ];

    protected $casts = [
        'status' => 'boolean', // on_sale 是一个布尔类型的字段
    ];

    // 与路线表Route关联
    public function route()
    {
        return $this->hasOne(Route::class,'id','town_id');
    }

    public function shoppingCarts(){
        return $this->hasMany(ShoppingCart::class);
    }
}
