<?php

namespace App\Admin\Controllers;

use App\Model\Order;
use App\Model\Route;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Admin\HandleRefundRequest;//校验商家处理退货申请请求
use Encore\Admin\Widgets\Box;

class OrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */

    protected function grid($status)
    {
        $grid = new Grid(new Order);

        $grid->model()->whereNotNull('paid_at')->where('delivery_status',$status)->orderBy('paid_at', 'desc');

        $grid->order_number('订单流水号');
        // 展示关联关系的字段时，使用 column 方法
        $grid->column('customer.name', '客户姓名');
        $grid->total('总金额')->sortable();
        $grid->total_profit('总利润')->sortable();
        $grid->column('路线')->display(function(){
            return $this->customer->route->route_id.'号路线';
        });
        $grid->paid_at('支付时间')->sortable();
        $grid->delivery_status('配送状态')->display(function($value) {
            return Order::$shipStatusMap[$value];
        });
        $grid->refund_status('退货状态')->display(function($value) {
            return Order::$refundStatusMap[$value];
        });
        $grid->exchange_status('换货状态')->display(function($value) {
            return Order::$exchangeStatusMap[$value];
        });
        // 禁用创建按钮，后台不需要创建订单
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 禁用删除和编辑按钮
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        return $grid;
    }

    public function index( Content $content)
    {
        return $content
            ->header('待处理订单')
            // body 方法可以接受 Laravel 的视图作为参数
            ->body(OrdersController::grid(['status'=>'pending']));
    }

    public function finished(Content $content){
        return $content
            ->header('已完成订单')
            // body 方法可以接受 Laravel 的视图作为参数
            ->body(OrdersController::grid(['status'=>'received']));
    }

    public function delivery( Content $content)
    {
        $orders=Order::where('paid_at','!=','null')->where('delivery_status','delivered')->get();
        $routes=Route::where('route_id','!=','0')->distinct()->get('route_id');

        return $content
            ->header('待配送订单')
            // body 方法可以接受 Laravel 的视图作为参数
            ->body(view('admin.orders.delivery_order',['routes'=>$routes,'orders'=>$orders]));
    }

     public function lay($refund,$exchange){
        $grid = new Grid(new Order);

        $grid->model()->whereNotNull('paid_at')->where('delivery_status','received')->where('refund_status',$refund)
             ->where('exchange_status',$exchange)->orderBy('paid_at', 'desc');//客户确认送达，且申请退货的订单

        $grid->order_number('订单流水号');
        // 展示关联关系的字段时，使用 column 方法
        $grid->column('customer.name', '客户姓名');
        $grid->total('总金额')->sortable();
        $grid->column('路线')->display(function(){
            return $this->customer->route->route_id.'号路线';
        });
        $grid->paid_at('支付时间')->sortable();
        $grid->delivery_status('配送状态')->display(function($value) {
            return Order::$shipStatusMap[$value];
        });
        $grid->refund_status('退款状态')->display(function($value) {
            return Order::$refundStatusMap[$value];
        });
        $grid->exchange_status('换货状态')->display(function($value) {
            return Order::$exchangeStatusMap[$value];
        });
        // 禁用创建按钮，后台不需要创建订单
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 禁用删除和编辑按钮
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    public function refund(Content $content){
        return $content
            ->header('申请退货订单')
            // body 方法可以接受 Laravel 的视图作为参数
            ->body(OrdersController::lay(['refund'=>'applied'],['exchange'=>'pending']));
    }

    public function exchange(Content $content){
        return $content
            ->header('申请换货订单')
            // body 方法可以接受 Laravel 的视图作为参数
            ->body(OrdersController::lay(['refund'=>'pending'],['exchange'=>'applied']));
    }

    public function show($id, Content $content)//订单详情
    {
        return $content
            ->header('查看订单')
            // body 方法可以接受 Laravel 的视图作为参数
            ->body(view('admin.orders.show', ['order' => Order::find($id)]));
    }

    public function ship(Order $order, Request $request)//处理订单
    {
        // 判断当前订单是否已支付
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未付款');
        }
        // 判断当前订单发货状态是否为未发货
        if ($order->delivery_status !== Order::DELIVERY_STATUS_PENDING) {
            throw new InvalidRequestException('该订单已配送');
        }
        // 将订单发货状态改为已发货
        $order->update([
            'delivery_status' => Order::DELIVERY_STATUS_DELIVERED,
        ]);

        // 返回上一页
        return redirect()->back();
    }

    public function handleExchange(Order $order, HandleRefundRequest $request)
    {
        // 判断订单状态是否正确
        if ($order->exchange_status !== Order::REFUND_STATUS_APPLIED) {
            throw new InvalidRequestException('订单状态不正确');
        }
        // 是否同意换货
        if ($request->input('agree')) {
             // 清空拒绝换货理由
            $extra = $order->extra ?: [];
            unset($extra['refund_disagree_reason']);
            $order->update([
                'exchange_status' => Order::REFUND_STATUS_SUCCESS,
                'extra' => $extra,
            ]);
        } else {
            // 将拒绝换货理由放到订单的 extra 字段中
            $extra = $order->extra ?: [];
            $extra['refund_disagree_reason'] = $request->input('reason');
            // 将订单的退款状态改为未退款
            $order->update([
                'exchange_status' => Order::REFUND_STATUS_PENDING,
                'extra'         => $extra,
            ]);
        }

        return $order;
    }
    public function handleRefund(Order $order, HandleRefundRequest $request) //商家处理退货申请
    {
        // 判断订单状态是否正确
        if ($order->refund_status !== Order::REFUND_STATUS_APPLIED) {
            throw new InvalidRequestException('订单状态不正确');
        }
        // 是否同意退款
        if ($request->input('agree')) {
            // 清空拒绝退款理由
            $extra = $order->extra ?: [];
            unset($extra['refund_disagree_reason']);
            $order->update([
                'extra' => $extra,
            ]);
            // 调用退款逻辑
            $this->_refundOrder($order);
        } else {
            // 将拒绝退款理由放到订单的 extra 字段中
            $extra = $order->extra ?: [];
            $extra['refund_disagree_reason'] = $request->input('reason');
            // 将订单的退款状态改为未退款
            $order->update([
                'exchange_status' => Order::REFUND_STATUS_PENDING,
                'extra'         => $extra,
            ]);
        }

        return $order;
    }

    protected function _refundOrder(Order $order)
    {
        // 判断该订单的支付方式
        switch ($order->payment_method) {
            case 'wechat':
                // 微信的先留空
                // todo
                break;
            case 'alipay':
                // 用我们刚刚写的方法来生成一个退款订单号
                $refundNo = Order::getAvailableRefundNo();
                // 调用支付宝支付实例的 refund 方法
                $ret = app('alipay')->refund([
                    'out_trade_no' => $order->order_number, // 之前的订单流水号
                    'refund_amount' => $order->total, // 退款金额，单位元
                    'out_request_no' => $refundNo, // 退款订单号
                ]);
                // 根据支付宝的文档，如果返回值里有 sub_code 字段说明退款失败
                if ($ret->sub_code) {
                    // 将退款失败的保存存入 extra 字段
                    $extra = $order->extra;
                    $extra['refund_failed_code'] = $ret->sub_code;
                    // 将订单的退款状态标记为退款失败
                    $order->update([
                        'refund_no' => $refundNo,
                        'refund_status' => Order::REFUND_STATUS_FAILED,
                        'extra' => $extra,
                    ]);
                } else {
                    // 将订单的退款状态标记为退款成功并保存退款订单号
                    $order->update([
                        'refund_no' => $refundNo,
                        'refund_status' => Order::REFUND_STATUS_SUCCESS,
                    ]);
                }
                break;
            default:
                // 原则上不可能出现，这个只是为了代码健壮性
                throw new InternalException('未知订单支付方式：'.$order->payment_method);
                break;
        }
    }
}
