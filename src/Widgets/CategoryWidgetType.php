<?php namespace Minhbang\Category\Widgets;

use Minhbang\Kit\Support\HasRouteAttribute;
use Minhbang\Layout\WidgetTypes\WidgetType;
use CategoryManager;
use Minhbang\Category\Category;

/**
 * Class CategoryWidget
 *
 * @package Minhbang\Category\Widgets
 */
abstract class CategoryWidgetType extends WidgetType
{
    use HasRouteAttribute;

    /**
     * @return string
     */
    abstract protected function categoryType();

    /**
     * @param \Minhbang\Layout\Widget|string $widget
     *
     * @return string
     */
    public function titleBackend($widget)
    {
        $category = $this->getCategory($widget);
        $title = $category ? ($category->isRoot() ? '' : $category->title) : $widget;

        return parent::titleBackend($title);
    }

    /**
     * @return array
     */
    public function formOptions()
    {
        return ['width' => null] + parent::formOptions();
    }

    /**
     * @return string
     */
    protected function formView()
    {
        return 'category::widget.category_form';
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function getCategoryTree($widget)
    {
        return ($category = $this->getCategory($widget)) ? $category->present()->tree(null, $widget->data['max_depth']) : '';
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return CategoryManager::of($this->categoryType())->selectize();
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return Category|null
     */
    protected function getCategory($widget)
    {
        return $widget->data['category_id'] ? Category::find($widget->data['category_id']) : CategoryManager::of($this->categoryType())->node();
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function content($widget)
    {
        $category_tree = $this->getCategoryTree($widget);

        return view('category::widget.category_output', compact('widget', 'category_tree'))->render();
    }

    /**
     * @return array
     */
    protected function dataAttributes()
    {
        return [
            [
                'name' => 'category_id',
                'title' => __('Category'),
                'rule' => '',
                'default' => null,
            ],
            [
                'name' => 'route_show',
                'title' => __('Category page route'),
                'rule' => 'required|max:255',
                'default' => '',
            ],
            [
                'name' => 'max_depth',
                'title' => __('Max depth'),
                'rule' => 'required|integer|min:1',
                'default' => 1,
            ],
        ];
    }
}