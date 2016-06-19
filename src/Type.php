<?php
namespace Minhbang\Category;

use Minhbang\Kit\Traits\Presenter\NestablePresenter;
use CategoryManager;

/**
 * Class Root
 * Quản lý một category 'type', ex: Article, Product,...
 * Một Type gồm 1 node root dùng quản lý, và các node con mới thật sự chứa 'content'
 *
 * @package Minhbang\Category
 */
class Type
{
    use NestablePresenter;
    /**
     * Được tạo từ content class name
     *
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $title;
    /**
     * Node gốc
     *
     * @var \Minhbang\Category\Category
     */
    protected $root;

    /**
     * Cached 'nodes con' TRỰC TIẾP của root (immediate descendants)
     *
     * @var \Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Category[]
     */
    protected $root1s;

    /**
     * @var int
     */
    public $max_depth;

    /**
     * Manager constructor.
     *
     * @param string $name
     * @param string $title
     * @param int $max_depth
     */
    function __construct($name, $title, $max_depth)
    {
        $this->name = $name;
        $this->title = $title;
        $this->max_depth = $max_depth;
    }

    /**
     * Render html theo định dạng của jquery nestable plugin
     *
     * @return string
     */
    public function nestable()
    {
        return $this->toNestable($this->root(), $this->max_depth);
    }

    /**
     * Tạo data select tag theo định dạng selectize
     *
     * @return array
     */
    public function selectize()
    {
        return $this->toSelectize($this->root1s());
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
        return $this->toTree($this->root(), $selected);
    }

    /**
     * @param string $attribute
     * @param string $key
     *
     * @return array|\Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Category[]
     */
    public function root1s($attribute = null, $key = 'id')
    {
        if (is_null($this->root1s)) {
            $this->root1s = $this->root()->getImmediateDescendants();
        }

        return $attribute ? $this->root1s->pluck($attribute, $key)->all() : $this->root1s;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * Lấy node gốc
     *
     * @return \Minhbang\Category\Category
     */
    public function root()
    {
        if (!$this->root) {
            $this->root = Category::findRootBySlugOrCreate(CategoryManager::getName($this->name));
        }

        return $this->root;
    }

    /**
     * Tạo data buttons từ danh sách $suffixs
     *
     * @param array $subtypes
     * @param string $url
     * @param string $size
     * @param string $active
     * @param string $default
     *
     * @return array
     */
    /*public function buttons($subtypes, $url, $size = 'sm', $active = 'primary', $default = 'white')
    {
        $buttons = [];
        if ($this->subtype) {
            foreach ($subtypes as $subtype) {
                $buttons[] = [
                    str_replace('TYPE', $subtype, $url),
                    CategoryManager::types($this->name, $subtype),
                    ['size' => $size, 'type' => $subtype == $this->subtype ? $active : $default],
                ];
            }
        }

        return $buttons;
    }*/
}