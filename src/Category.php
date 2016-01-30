<?php
namespace Minhbang\Category;

use Session;

/**
 * Class Category
 *
 * @package Minhbang\Category
 */
class Category
{
    /**
     * @var \Minhbang\Category\Category
     */
    protected $managers = [];
    /**
     * @var int
     */
    protected $max_depth;
    /**
     * @var array
     */
    protected $types = [];

    /**
     * UserManager constructor.
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
     * Lấy manager của category $type
     *
     * @param string|null $key
     * @param string|null $type
     *
     * @return \Minhbang\Category\Manager
     */
    public function manage($type = null, $key = null)
    {
        $type = $type ?: config('category.default_type');
        if (!isset($this->types[$type])) {
            if ($key) {
                Session::forget($key);
            }
            abort(404, trans('category::type.invalid'));
        }
        if (!isset($this->managers[$type])) {
            $this->managers[$type] = new Manager($type, $this->max_depth);
        }

        return $this->managers[$type];
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