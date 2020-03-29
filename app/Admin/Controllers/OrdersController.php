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
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->model()->whereNotNull('paid_at')->orderBy('paid_at', 'desc');

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

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_number', __('Order number'));
        $show->field('customer_id', __('Customer id'));
        $show->field('total', __('Total'));
        $show->field('remark', __('Remark'));
        $show->field('paid_at', __('Paid at'));
        $show->field('payment_method', __('Payment method'));
        $show->field('payment_no', __('Payment no'));
        $show->field('refund_status', __('Refund status'));
        $show->field('refund_no', __('Refund no'));
        $show->field('closed', __('Closed'));
        $show->field('delivery_status', __('Delivery status'));
        $show->field('extra', __('Extra'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->text('order_number', __('Order number'));
        $form->number('customer_id', __('Customer id'));
        $form->decimal('total', __('Total'));
        $form->textarea('remark', __('Remark'));
        $form->datetime('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', __('Payment method'));
        $form->text('payment_no', __('Payment no'));
        $form->text('refund_status', __('Refund status'))->default('pending');
        $form->text('refund_no', __('Refund no'));
        $form->switch('closed', __('Closed'));
        $form->text('delivery_status', __('Delivery status'))->default('pending');
        $form->textarea('extra', __('Extra'));

        return $form;
    }

    // public function index( Content $content)
    // {
    //     return $content
    //         ->header('订单列表')
    //         // body 方法可以接受 Laravel 的视图作为参数
    //         ->body(view('admin.orders.index'));
    // }

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
        // Laravel 5.5 之后 validate 方法可以返回校验过的值
        // $data = $this->validate($request, [
        //     'express_company' => ['required'],
        //     'express_no'      => ['required'],
        // ], [], [
        //     'express_company' => '物流公司',
        //     'express_no'      => '物流单号',
        // ]);
        // 将订单发货状态改为已发货，并存入物流信息
        $order->update([
            'delivery_status' => Order::DELIVERY_STATUS_DELIVERED,
            // 我们在 Order 模型的 $casts 属性里指明了 ship_data 是一个数组
            // 因此这里可以直接把数组传过去
            // 'ship_data'   => $data,
        ]);

        // 返回上一页
        return redirect()->back();
    }

    public function deliveryorder( Content $content)
    {
        $orders=Order::where('paid_at','!=','null')->where('delivery_status','delivered')->get();
        $routes=Route::where('route_id','!=','0')->distinct()->get('route_id');

        return $content
            ->header('待配送订单')
            // body 方法可以接受 Laravel 的视图作为参数
            ->body(view('admin.orders.delivery_order',['routes'=>$routes,'orders'=>$orders]));
    }

    public function handleRefund(Order $order, HandleRefundRequest $request) //商家处理退货申请
    {
        // 判断订单状态是否正确
        if ($order->refund_status !== Order::REFUND_STATUS_APPLIED) {
            throw new InvalidRequestException('订单状态不正确');
        }
        // 是否同意退款
        if ($request->input('agree')) {
            // 同意退款的逻辑这里先留空
            // todo
        } else {
            // 将拒绝退款理由放到订单的 extra 字段中
            $extra = $order->extra ?: [];
            $extra['refund_disagree_reason'] = $request->input('reason');
            // 将订单的退款状态改为未退款
            $order->update([
                'refund_status' => Order::REFUND_STATUS_PENDING,
                'extra'         => $extra,
            ]);
        }

        return $order;
    }

}
