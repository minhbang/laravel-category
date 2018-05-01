<?php namespace Minhbang\Category\Menus;

use Minhbang\Kit\Support\HasRouteAttribute;
use Minhbang\Menu\Types\MenuType;
use Minhbang\Category\Category;
use CategoryManager;

/**
 * Class CategoryMenuType
 *
 * @package Minhbang\Category\Menus
 */
abstract class CategoryMenuType extends MenuType
{
    use HasRouteAttribute;

    /**
     * @return string
     */
    abstract protected function categoryType();

    /**
     * @return array
     */
    public function formOptions()
    {
        return ['height' => 370] + parent::formOptions();
    }

    /**
     * @return string
     */
    protected function formView()
    {
        return 'category::menu.category_form';
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return CategoryManager::of($this->categoryType())->selectize();
    }

    /**
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return Category|null
     */
    protected function getCategory($menu)
    {
        return empty($menu->params['category_id']) ? null : Category::find($menu->params['category_id']);
    }

    /**
     * @param \Minhbang\Menu\Menu $menu
     * @return string
     */
    protected function buildUrl($menu)
    {
        $category = $this->getCategory($menu);

        return $category ? $this->getRouteUrl($menu->params['route_show'], ['slug' => $category->slug]) : "#{$menu->params['route_show']}";
    }

    /**
     * @return array
     */
    protected function paramsAttributes()
    {
        return [
            [
                'name' => 'category_id',
                'title' => __('Category'),
                'rule' => 'required|integer',
                'default' => null,
            ],
            [
                'name' => 'route_show',
                'title' => __('Route show'),
                'rule' => 'required|max:255',
                'default' => '',
            ],
        ];
    }
}