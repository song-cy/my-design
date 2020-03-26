<?php

namespace App\Admin\Controllers;

use App\Model\Customer;
use App\Model\Route;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;


class CustomersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customer);

        // 创建一个列名为 ID 的列，内容是客户的 id 字段
        $grid->id('ID');
        $grid->username('登录名');
        $grid->name('客户姓名');
        $grid->phone('联系电话');
        $grid->shop_name('商店名称');
        $grid->column('route.name','所属乡镇');
        $grid->dress('详细地址');
        $grid->created_at('注册时间');
        $grid->updated_at('更新时间');
        $grid->status('状态')->display(function(){
            if ($this->status == 1) {
                return '已启用';
            }
            return '已禁用';
        });

        $grid->quickSearch('username','name','phone');//搜索

        //禁用查询过滤器
        $grid->disableFilter();
        //禁用导出数据按钮
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableView();  //隐藏显示按钮
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
        $show = new Show(Customer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('username', __('Username'));
        $show->field('name', __('Name'));
        $show->field('password', __('Password'));
        $show->field('phone', __('Phone'));
        $show->field('shop_name', __('Shop name'));
        $show->field('town_id', __('Town id'));
        $show->field('dress', __('Dress'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Customer);

        $form->text('username', '客户登录名')->rules('required');
        $form->text('name', '客户姓名')->rules('required');

        $form->password('password', trans('密码'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('确认密码'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });
        $form->ignore(['password_confirmation']);

        $form->mobile('phone', '联系方式')->rules('required');
        $form->text('shop_name', '商店名称');

        $form->select('country_id','请选择所属县')
             ->options(Route::where('p_id','=','0')->where('id','!=','0')->pluck('name','id'))
             ->rules('required',['required'=>'请选择所属县'])
             ->load('town_id', '/town');
        $form->select('town_id','请选择所属乡镇')->options(function($id){
              return Route::where('p_id',$id)->pluck('name','id');
        })->rules('required',['required'=>'请选择所属乡镇']);
        $form->ignore(['country_id']);

        $form->text('dress', '详细地址')->rules('required');
        $form->radio('status', '状态')->options(['1' => '启用', '0'=> '禁用'])->default('1');
        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        return $form;
    }
}
