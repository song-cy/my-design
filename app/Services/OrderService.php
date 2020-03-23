<?php

namespace App\Services;

use App\Model\Customer;
// use App\Models\UserAddress;
use App\Model\Order;
use App\Model\ProductSku;
use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use Carbon\Carbon;

class OrderService
{
    public function store(Customer $customer,$remark, $items)
    {
        // 开启一个数据库事务
        $order = \DB::transaction(function () use ($customer, $remark, $items) {
            // 创建一个订单
            $order   = new Order([
                'remark'       => $remark,
                'total' => 0,
            ]);
            // 订单关联到当前用户
            $order->customer()->associate($customer);
            // 写入数据库
            $order->save();

            $total = 0;
            // 遍历用户提交的 SKU
            foreach ($items as $data) {
                $sku  = ProductSku::find($data['sku_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'quantity' => $data['amount'],
                    'price'  => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();
                $total += $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('该商品库存不足');
                }
            }
            // 更新订单总金额
            $order->update(['total' => $total]);

            // 将下单的商品从购物车中移除
            $skuIds = collect($items)->pluck('sku_id')->all();
            app(CartService::class)->remove($skuIds);

            return $order;
        });

        // 这里我们直接使用 dispatch 函数
        dispatch(new CloseOrder($order, config('app.order_ttl')));

        return $order;
    }
}
