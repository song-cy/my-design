<?php

namespace App\Admin\Controllers;

use App\Model\Finance;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FinancesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '资金明细';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Finance);

        $grid->column('id', __('ID'));
        $grid->column('title', __('名称'));
        $grid->column('type', __('类型'));
        $grid->column('total', __('金额'));
        $grid->column('created_at', __('创建时间'));

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
        $show = new Show(Finance::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('title', __('名称'));
        $show->field('type', __('类型'));
        $show->field('total', __('金额'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Finance);

        $form->textarea('title', __('Title'));
        $form->select('type','类型')->options(['支出'=>'支出','亏损'=>'亏损','收入'=>'收入'])
             ->rules('required',['required'=>'请选择类型']);
        $form->decimal('total', __('金额'));

        return $form;
    }
}
