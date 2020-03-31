<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use App\Model\Customer;
use App\Model\Product;
use App\Model\Order;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        // return $content
        //     ->title('Dashboard')
        //     ->description('Description...')
        //     ->row(Dashboard::title())
        //     ->row(function (Row $row) {

        //         $row->column(4, function (Column $column) {
        //             $column->append(Dashboard::environment());
        //         });

        //         $row->column(4, function (Column $column) {
        //             $column->append(Dashboard::extensions());
        //         });

        //         $row->column(4, function (Column $column) {
        //             $column->append(Dashboard::dependencies());
        //         });
        //     });

        $customer=Customer::count();
        $employe=config('admin.database.users_model')::count();
        $product=Product::count();
        $pending=Order::whereNotNull('paid_at')->where('delivery_status','pending')->count();
        $delivery=Order::whereNotNull('paid_at')->where('delivery_status','delivered')->count();
        $refund=Order::whereNotNull('paid_at')->where('delivery_status','received')->where('refund_status','applied')->count();
        return $content
            ->header('首页')
            ->body(new Box('统计', view('admin.chart.charts',compact('customer','employe','product','pending','delivery','refund'))));
    }
}
