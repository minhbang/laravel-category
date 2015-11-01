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
     * Category groups list
     *
     * @var array
     */
    public $groups;

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

    protected $scenario = 'all';

    /**
     * @param \Minhbang\LaravelCategory\CategoryFactory $factory
     * @param integer $max_depth
     */
    function __construct($factory, $max_depth)
    {
        $this->types = $factory->getTypes();
        $this->groups = $factory->getGroups();
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
        return $this->toNestable($this->getRoots(), $this->max_depth);
    }

    /**
     * Tạo data select tag theo định dạng selectize
     *
     * @return string
     */
    public function selectize()
    {
        return $this->toSelectize($this->getRoots());
    }

    /**
     * @param string $category
     *
     * @return bool
     */
    public function hasType($category)
    {
        return isset($this->types[$category]);
    }

    /**
     * @param string|null $type
     * @param mixed $default
     *
     * @return string
     */
    public function getTypeName($type = null, $default = null)
    {
        $type = $type ?: $this->root->slug;
        return isset($this->types[$type]) ? $this->types[$type] : $default;
    }

    /**
     * @return string
     */
    public function getTypeSlug()
    {
        return $this->root->slug;
    }

    /**
     * @param string|null $type
     *
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
     * @param string $group
     * @param mixed $default
     *
     * @return mixed
     */
    public function getGroup($group, $default = null)
    {
        return isset($this->groups[$group]) ? $this->groups[$group] : $default;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelCategory\CategoryItem[]
     */
    public function getRoots()
    {
        return $this->root->getImmediateDescendants();
    }

    /**
     * @param string $attribute
     * @param string $key
     */
    public function getListRoots($attribute = 'title', $key = 'id')
    {
        return $this->root->immediateDescendants()->lists($attribute, $key)->all();
    }

    /**
     * Thao tác với category $type
     *
     * @param string $type
     *
     * @return static
     */
    public function of($type)
    {
        $this->switchType($type);
        return $this;
    }

    /**
     * @param string $scenario
     *
     * @return static
     */
    public function manage($scenario)
    {
        $this->scenario = $scenario;
        return $this;
    }

    /**
     * Chuyển category type hiện tại, 404 khi type chưa được khai báo
     *
     * @param string|null $type
     */
    public function switchType($type = null)
    {
        $key = "category_type_for_{$this->scenario}";
        $type = $type ?: session($key, config('category.default_type'));
        $this->root = $this->getTypeRoot($type);
        if (!$this->root) {
            session([$key => null]);
            abort(404, trans('category::common.not_found'));
        }
        session([$key => $type]);
    }
}