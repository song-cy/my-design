<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
// use App\Model\ProductSku;
use App\Model\Order;
// use Carbon\Carbon;
use App\Exceptions\InvalidRequestException;
// use App\Jobs\CloseOrder;
use Illuminate\Http\Request;
// use App\Services\CartService;
use App\Services\OrderService;

class OrdersController extends Controller
{
    // 利用 Laravel 的自动解析功能注入 CartService 类
    public function store(OrderRequest $request, OrderService $orderService)
    {
        $customer    = $request->user();
        // $address = UserAddress::find($request->input('address_id'));

        return $orderService->store($customer, $request->input('remark'), $request->input('items'));
    }

    public function index(Request $request)
    {
        $orders = Order::query()
            // 使用 with 方法预加载，避免N + 1问题
            ->with(['items.product', 'items.productSku'])
            ->where('customer_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Order $order, Request $request)
    {
        $this->authorize('own', $order);
        return view('orders.show', ['order' => $order->load(['items.productSku', 'items.product'])]);
        //loda()延迟预加载,是在已经查询出来的模型上调用，而 with() 则是在 ORM 查询构造器上调用
    }

    public function received(Order $order, Request $request)
    {
        // 校验权限
        $this->authorize('own', $order);

        // 判断订单的发货状态是否为已发货
        if ($order->delivery_status !== Order::DELIVERY_STATUS_DELIVERED) {
            throw new InvalidRequestException('发货状态不正确');
        }

        // 更新发货状态为已收到
        $order->update(['delivery_status' => Order::DELIVERY_STATUS_RECEIVED]);

        // 返回原页面
        return $order;
    }
}
