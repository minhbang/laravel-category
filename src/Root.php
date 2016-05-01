<?php
namespace Minhbang\Category;

use Minhbang\Kit\Traits\Presenter\NestablePresenter;
use CategoryManager;

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
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $suffix;
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
     * @param string $suffix
     */
    function __construct($type, $max_depth, $suffix = null)
    {
        $this->type = $type;
        $this->max_depth = $max_depth;
        $this->suffix = $suffix;
    }

    /**
     * Render html theo định dạng của jquery nestable plugin
     *
     * @return string
     */
    public function nestable()
    {
        return $this->toNestable($this->node(), $this->max_depth);
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
        return $this->toTree($this->node(), $selected);
    }

    /**
     * @param string $attribute
     * @param string $key
     *
     * @return array|\Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Category[]
     */
    public function roots($attribute = null, $key = 'id')
    {
        if (is_null($this->roots)) {
            $this->roots = $this->node()->getImmediateDescendants();
        }

        return $attribute ? $this->roots->pluck($attribute, $key)->all() : $this->roots;
    }

    /**
     * @return array
     */
    public function types()
    {
        return CategoryManager::types($this->type, '*');
    }

    /**
     * @return string
     */
    public function type()
    {
        return CategoryManager::types($this->type, $this->suffix);
    }

    /**
     * Lấy node gốc
     *
     * @return \Minhbang\Category\Category
     */
    public function node()
    {
        if (!$this->node) {
            $this->node = Category::findRootBySlugOrCreate(CategoryManager::typeValue($this->type, $this->suffix));
        }

        return $this->node;
    }

    /**
     * @param array $suffixs
     * @param string $url
     * @param string $size
     * @param string $active
     * @param string $default
     *
     * @return array
     */
    public function buttons($suffixs, $url, $size = 'sm', $active = 'primary', $default = 'white')
    {
        $buttons = [];
        if ($this->suffix) {
            foreach ($suffixs as $suffix) {
                $buttons[] = [
                    str_replace('TYPE', $suffix, $url),
                    CategoryManager::types($this->type, $suffix),
                    ['size' => $size, 'type' => $suffix == $this->suffix ? $active : $default],
                ];
            }
        }

        return $buttons;
    }
}