<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Type;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->column('id', __('Id'))->sortable();

        $grid->column('type_id', __('Type id'))->display(function ($type_id) {
            return Type::where('id',$type_id)->value('name_zh');
        });
        $grid->column('category_id', __('Category id'))->display(function ($category_id) {
            return Category::where('id',$category_id)->value('name');
        });

        $grid->column('title', __('Title'))->display(function ($title) {
            return Str::limit($title, $limit = 20, $end = '...');
        });
        //$grid->column('description', __('Description'));
        $grid->column('image_url', __('Image url'))->image(config('url').'/storage/', 30, 30);
        $grid->column('commission_rate', __('Commission rate'))->editable()->sortable();
        $grid->column('commission', __('Commission'))->editable()->sortable();
        $grid->column('discounted_price', __('Discounted price'))->editable()->sortable();
        $grid->column('price', __('Price'))->editable()->sortable();
        $grid->column('favourable_price', __('Favourable price'))->editable()->sortable();
        $grid->column('vermicelli_consumption', __('Vermicelli consumption'))->editable()->sortable();
        $grid->column('sample_quantity', __('Sample quantity'))->editable()->sortable();
        $grid->column('support_dou', __('Support dou'))->display(function ($support_dou) {
            return $support_dou ? '是' : '否';
        });
        $grid->column('support_directional', __('Support directional'))->display(function ($support_directional) {
            return $support_directional ? '是' : '否';
        });
        // on_sale
        $grid->column('on_sale', __('显示否'))->display(function ($support_directional) {
            return $support_directional ? '是' : '否';
        });;
        $grid->column('copy_link', __('Copy link'))->link();
        $grid->column('activity_countdown', __('Activity countdown'));
        $grid->column('sort_num', __('Sort num'))->editable()->sortable();

        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function($filter){
            $type = new Type();
            $category = new Category();
            // 在这里添加字段过滤器
            $filter->like('title', '商品名称');
            $filter->like('copy_link', '链接');
            $filter->equal('type_id','所属类型')->select($type->orderSort()->pluck('name_zh','id'));
            $filter->equal('category_id','所属分类')->select($category->orderSort()->pluck('name','id'));
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('image_url', __('Image url'))->image(config('url').'/storage/');
        $show->field('description', __('Description'))->unescape();
        $show->field('commission_rate', __('Commission rate'));
        $show->field('commission', __('Commission'));
        $show->field('discounted_price', __('Discounted price'));
        $show->field('price', __('Price'));
        $show->field('favourable_price', __('Favourable price'));
        $show->field('vermicelli_consumption', __('Vermicelli consumption'));
        $show->field('sample_quantity', __('Sample quantity'));
        $show->field('support_dou', __('Support dou'));
        $show->field('support_directional', __('Support directional'));
        $show->field('copy_link', __('Copy link'));
        $show->field('activity_countdown', __('Activity countdown'));
        $show->field('type_id', __('Type id'));
        $show->field('category_id', __('Category id'));
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
        $form = new Form(new Product());

        $form->select('type_id',__('Type id'))->options(Type::pluck('name_zh','id'));
        $form->select('category_id',__('Category id'))->options(Category::pluck('name','id'));
        $form->text('title', __('Title'))->rules('required');
        $form->image('image_url', __('商品主图'))->rules('required');
        $form->UEditor('description', __('Description'))->rules('required');
        $form->decimal('commission_rate', __('Commission rate'));
        $form->hidden('commission', __('Commission'))->default(0);

        $form->decimal('discounted_price', __('Discounted price'))->default(0.00)->rules('required');
        $form->decimal('price', __('Price'))->default(0.00)->rules('required');
        $form->decimal('favourable_price', __('Favourable price'))->default(0.00)->rules('required');
        $form->decimal('vermicelli_consumption', __('Vermicelli consumption'))->rules('required');
        $form->decimal('sample_quantity', __('Sample quantity'))->rules('required');
        $form->switch('support_dou', __('Support dou'))->default(1)->rules('required');
        $form->switch('support_directional', __('Support directional'))->default(1)->rules('required');
        $form->text('copy_link', __('Copy link'))->rules('required');
        $form->date('activity_countdown', __('Activity countdown'))->default(date('H:i:s'))->rules('required');
        $form->switch('on_sale', __('On sale'))->default(1);
        $form->number('sort_num', __('Sort num'))->default(0);
//        $form->number('type_id', __('Type id'));
//        $form->number('category_id', __('Category id'));
            //保存前回调
            $form->saving(function (Form $form) {
                if(request('commission') == $form->model()->commission) {
                    $commission_rate = (bcdiv($form->model()->commission_rate,100,2));
                    $commission = bcmul($form->model()->discounted_price,$commission_rate,2);
                    Product::where('id',$form->model()->id)->update(['commission'=>$commission]);
                }
            });

        return $form;
    }
}
