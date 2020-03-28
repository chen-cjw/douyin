<?php

namespace App\Admin\Controllers;

use App\Models\Banner;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BannerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '轮播图';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner());
        $grid->column('id', __('Id'))->sortable();
        $grid->column('image_url', __('Image Url'))->image(config('url').'/storage/', 30, 30);
        $grid->column('href_url', __('Href Url'))->display(function ($href_url) {
            return $href_url?:'暂无';
        });
        $grid->column('sort_num', __('Sort num'))->sortable();
        $grid->column('on_sale', __('On sale'))->display(function ($on_sale) {
                return $on_sale ? '是' : '否';
        });
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
        $show = new Show(Banner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('image_url', __('Image Url'))->image('/');
        $show->field('href_url', __('Href Url'));
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
        $form = new Form(new Banner());

        $form->image('image_url', __('Image Url'));
        $form->text('href_url', __('Href Url'));
        $form->number('sort_num', __('Sort num'))->default(0);
        $form->switch('on_sale', __('On sale'))->default(1);

        return $form;
    }
}
