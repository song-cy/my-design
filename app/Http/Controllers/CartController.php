<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddCartRequest;
use APP\Model\ShoppingCart;
use APP\Model\ProductSku;

class CartController extends Controller
{
    public function add(AddCartRequest $request)    //将商品添加至购物车
    {
        $customer   = $request->user();
        $skuId  = $request->input('sku_id');
        $quantity = $request->input('amount');

        // 从数据库中查询该商品是否已经在购物车中
        if ($cart = $customer->shoppingCarts()->where('product_sku_id', $skuId)->first()) {

            // 如果存在则直接叠加商品数量
            $cart->update([
                'quantity' => $cart->quantity + $quantity,
            ]);
        } else {

            // 否则创建一个新的购物车记录
            $cart = new ShoppingCart(['quantity' => $quantity]);
            $cart->customer()->associate($customer);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }

        return [];
    }

    public function index(Request $request)   //查看购物车
    {
        $cartItems = $request->user()->shoppingCarts()->with(['productSku.product'])->get();//with(['productSku.product']) 方法用来预加载购物车里的商品和 SKU 信息，把原本需要多条 SQL 查询的数据用一条 SQL 就查到了，大大提升了执行效率

        return view('shoppingcart.index', ['cartItems' => $cartItems]);
    }

    public function remove($sku, Request $request)   //将商品从购物车删除
    {
        $request->user()->shoppingCarts()->where('product_sku_id', $sku)->delete();

        return [];
    }
}
