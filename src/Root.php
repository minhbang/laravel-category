<?php
namespace Minhbang\Category;

use Minhbang\Kit\Traits\Presenter\NestablePresenter;

/**
 * Class Root
 * Quản lý 'Node gốc' của một category 'type'
 *
 * @package Minhbang\Category
 */
class Root
{
    use NestablePresenter;

    /**
     * Node gốc
     *
     * @var \Minhbang\Category\Category
     */
    protected $node;

    /**
     * Danh sách 'nodes con' TRỰC TIẾP của 'node gốc' (immediate descendants)
     *
     * @var \Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Category[]
     */
    protected $roots;
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
        $this->node = Category::findRootBySlugOrCreate($type);
    }

    /**
     * Render html theo định dạng của jquery nestable plugin
     *
     * @return string
     */
    public function nestable()
    {
        return $this->toNestable($this->node, $this->max_depth, false, function ($query) {
            return $query->with('translations');
        });
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
     * @param \Minhbang\Category\Category|mixed|null $selected
     *
     * @return string
     */
    public function tree($selected = null)
    {
        return $this->toTree($this->node, $selected, false, function ($query) {
            return $query->with('translations');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Category[]
     */
    public function roots()
    {
        if (is_null($this->roots)) {
            $this->roots = $this->node->getImmediateDescendants();
        }

        return $this->roots;
    }

    /**
     * @param string $attribute
     * @param string $key
     *
     * @return array
     */
    public function listRoots($attribute = 'title', $key = 'id')
    {
        return $this->roots()->pluck($attribute, $key)->all();
    }

    /**
     * @return array
     */
    public function typeNames()
    {
        return app('category-manager')->typeNames();
    }

    /**
     * @return string
     */
    public function typeName()
    {
        return app('category-manager')->typeNames($this->node->slug);
    }

    /**
     * Lấy node gốc
     *
     * @return \Minhbang\Category\Category
     */
    public function node()
    {
        return $this->node;
    }
}