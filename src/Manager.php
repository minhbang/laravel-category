<?php
namespace Minhbang\Category;

use Session;

/**
 * Class Manager
 *
 * @package Minhbang\Category
 */
class Manager
{
    /**
     * @var \Minhbang\Category\Root[]
     */
    protected $roots = [];

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var int
     */
    protected $max_depth;

    /**
     * Manager constructor.
     *
     * @param array $types
     * @param int $max_depth
     */
    public function __construct($types = ['article'], $max_depth = 5)
    {
        foreach ($types as $type) {
            $this->types[$type] = trans("category::type.{$type}");
        }
        $this->max_depth = $max_depth;
    }


    /**
     * Lấy root của category $type
     *
     * @param string|null $key
     * @param string|null $type
     *
     * @return \Minhbang\Category\Root
     */
    public function root($type = null, $key = null)
    {
        $type = $type ?: config('category.default_type');
        if (!isset($this->types[$type])) {
            if ($key) {
                Session::forget($key);
            }
            abort(404, trans('category::type.invalid'));
        }
        if (!isset($this->roots[$type])) {
            $this->roots[$type] = new Root($type, $this->max_depth);
        }

        return $this->roots[$type];
    }

    /**
     * @param string $type
     * @param mixed $default
     *
     * @return array
     */
    public function typeNames($type = null, $default = false)
    {
        if ($type) {
            return isset($this->types[$type]) ? $this->types[$type] : $default;
        } else {
            return $this->types;
        }
    }
}