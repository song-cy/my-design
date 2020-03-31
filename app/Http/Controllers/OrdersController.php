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
use App\Http\Requests\ApplyRefundRequest;//申请退货接口

class OrdersController extends Controller
{
    // 利用 Laravel 的自动解析功能注入 CartService 类
    public function store(OrderRequest $request, OrderService $orderService)
    {
        $customer = $request->user();

        return $orderService->store($customer, $request->input('remark'), $request->input('items'));
    }

    public function index(Request $request,$status='null')
    {
        if($status == 'obligation'){  //待付款订单
            $orders = Order::query()
            // 使用 with 方法预加载，避免N + 1问题
            ->with(['items.product', 'items.productSku'])
            ->where('customer_id', $request->user()->id)
            ->where('paid_at',null)
            ->where('closed','0')
            ->orderBy('created_at', 'desc')
            ->paginate();
        }else if($status =='delivery'){ //待配送订单
             $orders = Order::query()
            ->with(['items.product', 'items.productSku'])
            ->where('customer_id', $request->user()->id)
            ->where('paid_at','!=',null)
            ->whereIn('delivery_status',['pending','delivered'])
            ->orderBy('created_at', 'desc')
            ->paginate();
        }else{  //所有订单
            $orders = Order::query()
            ->with(['items.product', 'items.productSku'])
            ->where('customer_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate();
        }


        return view('orders.index', ['orders' => $orders,'sta'=>$status]);
    }

    public function show(Order $order, Request $request)
    {
        $this->authorize('own', $order);
        return view('orders.show', ['order' => $order->load(['items.productSku', 'items.product'])]);
        //loda()延迟预加载,是在已经查询出来的模型上调用，而 with() 则是在 ORM 查询构造器上调用
    }

    public function received(Order $order, Request $request)  //确认送达
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

    public function applyRefund(Order $order, ApplyRefundRequest $request)   //申请退货
    {
        // 校验订单是否属于当前用户
        $this->authorize('own', $order);
        // 判断订单是否已付款
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付，不可退款');
        }
        // 判断订单退款状态是否正确
        if ($order->refund_status !== Order::REFUND_STATUS_PENDING) {
            throw new InvalidRequestException('该订单已经申请过退款，请勿重复申请');
        }
        // 将用户输入的退款理由放到订单的 extra 字段中
        $extra                  = $order->extra ?: [];
        $extra['refund_reason'] = $request->input('reason');
        // 将订单退款状态改为已申请退款
        $order->update([
            'refund_status' => Order::REFUND_STATUS_APPLIED,
            'extra'         => $extra,
        ]);

        return $order;
    }
}
