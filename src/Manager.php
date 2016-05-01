<?php
namespace Minhbang\Category;

/**
 * Class Manager
 *
 * @package Minhbang\Category
 */
class Manager
{
    /**
     * Danh sách node gốc của các category types,
     * key chính là resource class name, vd: '\Minhbang\Article\Article'
     *
     * @var \Minhbang\Category\Root[]
     */
    protected $roots = [];

    /**
     * Danh sách type titles
     *
     * @var array
     */
    protected $types = [];

    /**
     * Đăng ký một category resource type
     *
     * @param string $type Resource class name
     * @param string $title Type title
     * @param int $max_depth
     * @param string $suffix Dùng khi đăng ký 1 resource có nhiều category type, vd: article có news, page...
     */
    public function register($type, $title, $max_depth, $suffix = null)
    {
        $key = $this->typeValue($type, $suffix);
        $type = $this->typeValue($type);
        $this->types[$key] = $title;
        $this->roots[$key] = new Root($type, $max_depth, $suffix);
    }

    /**
     * @param string $type
     * @param string $suffix
     *
     * @return \Minhbang\Category\Root
     */
    public function root($type, $suffix = null)
    {
        $type = $this->typeValue($type, $suffix);
        abort_unless(isset($this->roots[$type]), 500, "Category Manager: unregistered category type for $type!");

        return $this->roots[$type];
    }

    /**
     * @param string $type
     * @param string $suffix
     * @param mixed $default
     *
     * @return string|mixed
     */
    public function types($type = null, $suffix = null, $default = null)
    {
        if ($type && $suffix === '*') {
            $pattern = $this->typeValue($type) . '*';

            return array_where($this->types, function ($key) use ($pattern) {
                return str_is($pattern, $key);
            });
        } else {
            return array_get($this->types, $this->typeValue($type, $suffix), $default);
        }
    }

    /**
     * @param string $name
     * @param string $suffix
     *
     * @return string
     */
    public function typeValue($name, $suffix = null)
    {
        return $name ?
            strtolower(str_replace(['_', '\\', '.'], '-', $name)) . ($suffix ? "-{$suffix}" : '') :
            null;
    }

    /**
     * @param string $name
     * @param array $suffixs
     *
     * @return array
     */
    public function typeValues($name, $suffixs = [])
    {
        return array_map(
            function ($suffix) use ($name) {
                return $this->typeValue($name, $suffix);
            },
            $suffixs
        );
    }
}