<?php
namespace Minhbang\LaravelCategory;

use Minhbang\LaravelKit\Traits\Presenter\NestablePresenter;

/**
 * Class Category
 *
 * @package Minhbang\LaravelCategory
 */
class Category
{
    use NestablePresenter;
    /**
     * Category types list
     *
     * @var array
     */
    public $types;

    /**
     * @var integer max category level
     */
    public $max_depth;

    /**
     * Current type root
     *
     * @var \Minhbang\LaravelCategory\CategoryItem
     */
    public $root;

    /**
     * @param \Minhbang\LaravelCategory\CategoryFactory $factory
     * @param integer $max_depth
     */
    function __construct($factory, $max_depth)
    {
        $this->types = $factory->getTypes();
        $this->max_depth = $max_depth;
    }

    /**
     * Render html theo định dạng của jquery nestable plugin
     *
     * @see https://github.com/dbushell/Nestable
     * @return string
     */
    public function nestable()
    {
        return $this->toNestable($this->root->getImmediateDescendants(), $this->max_depth);
    }

    /**
     * Tạo data select tag theo định dạng selectize
     *
     * @return string
     */
    public function selectize()
    {
        return $this->toSelectize($this->root->getImmediateDescendants());
    }

    /**
     * @param string $category
     * @return bool
     */
    public function hasType($category)
    {
        return isset($this->types[$category]);
    }

    /**
     * @param string|null $type
     * @param mixed $default
     * @return string
     */
    public function getTypeName($type = null, $default = null)
    {
        $type = $type ?: $this->root->slug;
        return isset($this->types[$type]) ? $this->types[$type] : $default;
    }

    /**
     * @param string|null $type
     * @return \Minhbang\LaravelCategory\CategoryItem|null
     */
    protected function getTypeRoot($type = null)
    {
        $type = $type ?: config('category.default_type');
        if ($this->hasType($type)) {
            if ($root = CategoryItem::where('title', $type)->where('slug', $type)->first()) {
                return $root;
            }
            return CategoryItem::create(
                [
                    'title' => $type,
                    'slug'  => $type,
                ]
            );
        } else {
            return null;
        }
    }

    /**
     * Thao tác với category $type
     *
     * @param string $type
     * @return static
     */
    public function of($type)
    {
        $this->switchType($type);
        return $this;
    }

    /**
     * Chuyển category type hiện tại, 404 khi type chưa được khai báo
     *
     * @param string|null $type
     */
    public function switchType($type = null)
    {
        $type = $type ?: session('CategoryResource_type', config('category.default_type'));
        $this->root = $this->getTypeRoot($type);
        if (!$this->root) {
            session(['CategoryResource_type' => null]);
            abort(404, trans('category::common.not_found'));
        }
        session(['CategoryResource_type' => $type]);
    }
}