<?php
namespace Minhbang\Category;

use Minhbang\LaravelKit\Traits\Presenter\NestablePresenter;

/**
 * Class Manager
 *
 * @package Minhbang\Category
 */
class Manager
{
    use NestablePresenter;
    /**
     * Current type root
     *
     * @var \Minhbang\Category\Item
     */
    protected $_type_root;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Item[]
     */
    protected $_roots;
    /**
     * @var int
     */
    public $max_depth;

    /**
     * Manager constructor.
     *
     * @param string $type
     * @param int $max_depth
     */
    function __construct($type, $max_depth)
    {
        $this->max_depth = $max_depth;
        $this->_type_root = Item::firstOrCreate([
            'title' => $type,
            'slug'  => $type,
        ]);
    }

    /**
     * Render html theo định dạng của jquery nestable plugin
     *
     * @return string
     */
    public function nestable()
    {
        return $this->toNestable($this->roots(), $this->max_depth);
    }

    /**
     * Tạo data select tag theo định dạng selectize
     *
     * @return array
     */
    public function selectize()
    {
        return $this->toSelectize($this->roots());
    }

    /**
     * Tạo tree data cho bootstrap treeview
     *
     * @param \Minhbang\Category\Item|mixed|null $selected
     *
     * @return array
     */
    public function tree($selected = null)
    {
        return $this->toTree($this->roots(), $selected);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Item[]
     */
    public function roots()
    {
        if (is_null($this->_roots)) {
            $this->_roots = $this->_type_root->getImmediateDescendants();
        }

        return $this->_roots;
    }

    /**
     * @param string $attribute
     * @param string $key
     *
     * @return array
     */
    public function listRoots($attribute = 'title', $key = 'id')
    {
        return $this->roots()->lists($attribute, $key)->all();
    }

    /**
     * @return array
     */
    public function typeNames()
    {
        return app('category')->typeNames();
    }

    /**
     * @return string
     */
    public function typeName()
    {
        return app('category')->typeNames($this->_type_root->slug);
    }

    /**
     * @return \Minhbang\Category\Item|static
     */
    public function typeRoot()
    {
        return $this->_type_root;
    }
}