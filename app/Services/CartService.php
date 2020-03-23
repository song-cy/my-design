<?php

namespace App\Services;

use Auth;
use App\Model\ShoppingCart;

class CartService
{
    public function get()
    {
        return Auth::user()->shoppingCarts()->with(['productSku.product'])->get();//Auth::user()获取当前登录用户
    }

    public function add($skuId, $quantity)
    {
        $user = Auth::user();
        // 从数据库中查询该商品是否已经在购物车中
        if ($item = $user->shoppingCarts()->where('product_sku_id', $skuId)->first()) {
            // 如果存在则直接叠加商品数量
            $item->update([
                'quantity' => $item->quantity + $quantity,
            ]);
        } else {
            // 否则创建一个新的购物车记录
            $item = new ShoppingCart(['quantity' => $quantity]);
            $item->Customer()->associate($user);
            $item->productSku()->associate($skuId);
            $item->save();
        }

        return $item;
    }

    public function remove($skuIds)
    {
        // 可以传单个 ID，也可以传 ID 数组
        if (!is_array($skuIds)) {
            $skuIds = [$skuIds];
        }
        Auth::user()->shoppingCarts()->whereIn('product_sku_id', $skuIds)->delete();
    }
}
