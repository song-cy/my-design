<?php

namespace App\Admin\Controllers;

use App\Model\route;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;


class RouteController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\route';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new route);

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('p_id', __('P id'));
        $grid->column('route_id', __('Route id'));

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
        $show = new Show(route::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('p_id', __('P id'));
        $show->field('route_id', __('Route id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new route);

        $form->text('name', __('县/乡镇名称'));
        // $form->number('p_id', __('所属县'));
        $form->select('p_id','请选择所属县')
             ->options(Route::where('p_id','=','0')->pluck('name','id'));
        $form->number('route_id', __('路线'))->min('0');

        return $form;
    }

    public function index( Content $content)
    {
        $routes=Route::where('route_id','!=','0')->distinct()->get('route_id');
        $towns=DB::table('routes as r1')
              ->join('routes as r2','r1.p_id','=','r2.id')
              ->where('r1.p_id','!=','0')
              ->select('r1.*','r2.name as pname')
              ->get();

        return $content
            ->header('订单列表')
            // body 方法可以接受 Laravel 的视图作为参数
            ->body(view('admin.orders.index',['routes'=>$routes,'towns'=>$towns]));
    }
    // public function remove($id)
    // {
    //     $request=Route::where('id', $id)->delete();
    //     return [];
    // }
}
