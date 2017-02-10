<?php
namespace Minhbang\Category;

use Minhbang\Kit\Traits\Presenter\NestablePresenter;
use Minhbang\Kit\Support\VnString;

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
        $this->node      = Category::findRootBySlugOrCreate($type);
    }

    /**
     * Render html theo định dạng của jquery nestable plugin
     *
     * @return string
     */
    public function nestable()
    {
        return $this->toNestable($this->node, $this->max_depth);
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
        return $this->toTree($this->node, $selected);
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

    /**
     * @param array $path
     *
     * @return int
     */
    public function createNodesFromPath($path)
    {
        $root = $this->node;
        foreach ($path as $title) {
            if ($node = $root->descendants()->where('title', $title)->first()) {
                $root = $node;
            } else {
                $node = Category::create(['title' => $title, 'slug' => VnString::to_slug($title)]);
                $node->makeChildOf($root);
                $root = $node;
            }

        }

        return $root->id;
    }
}