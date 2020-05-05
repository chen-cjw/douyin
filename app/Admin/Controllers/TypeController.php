<?php

namespace App\Admin\Controllers;

use App\Models\Type;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '类型';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Type());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name_zh', __('Name zh'))->editable();
        $grid->column('name_en', __('Name en'))->editable();
        $grid->column('image_url', __('Image url'))->image('', 30, 30);
        $grid->column('sort_num', __('Sort num'))->editable()->sortable();
        $states = [
            'on'  => ['value' => 1, 'text' => '打开', 'color' => 'primary'],
            'off' => ['value' => 2, 'text' => '关闭', 'color' => 'default'],
        ];

        $grid->column('on_sale', __('On sale'))->switch($states);
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

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
        $show = new Show(Type::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name_zh', __('Name zh'));
        $show->field('name_en', __('Name en'));
        $show->field('image_url', __('Image url'))->image();
        $show->field('sort_num', __('Sort num'));
        $show->field('on_sale', __('On sale'));
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
        $form = new Form(new Type());

        $form->text('name_zh', __('Name zh'));
        $form->text('name_en', __('Name en'));
        $form->image('image_url', __('Image url'));
        $form->number('sort_num', __('Sort num'))->default(0);
        $form->switch('on_sale', __('On sale'))->default(1);

        return $form;
    }
}
