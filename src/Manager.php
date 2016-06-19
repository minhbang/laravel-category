<?php
namespace Minhbang\Category;

/**
 * Class Manager
 * Quản lý tất cả category types
 *
 * @package Minhbang\Category
 */
class Manager
{
    /**
     * Danh sách category types
     *
     * @var \Minhbang\Category\Type[]
     */
    protected $types = [];

    /**
     * Danh sách type titles
     *
     * @var array
     */
    protected $titles = [];

    /**
     * Đăng ký một category type
     *
     * @param string $class Content class name
     * @param array $settings
     * @param array $subtypes
     */
    public function register($class, $settings, $subtypes = [])
    {
        $max_depth = array_get($settings, 'max_depth');
        $title = array_get($settings, 'title');
        $name = $this->getName($class);
        if ($subtypes) {
            foreach ($subtypes as $type) {
                $this->addType("{$name}-{$type}", "{$title}.{$type}", $max_depth);
            }
        } else {
            $this->addType($name, $title, $max_depth);
        }
    }

    /**
     * @param string $name
     * @param string $title
     * @param int $max_depth
     */
    protected function addType($name, $title, $max_depth)
    {
        $title = trans($title);
        $this->titles[$name] = $title;
        $this->types[$name] = new Type($name, $title, $max_depth);
    }

    /**
     * Đã đăng ký $name type chưa?
     *
     * @param $type
     *
     * @return bool
     */
    public function has($type)
    {
        return isset($this->types[$type]);
    }

    /**
     * Lấy Category manager cho $model
     *
     * @param string|object $model
     * @param string $subtype
     *
     * @return \Minhbang\Category\Type
     */
    public function of($model, $subtype = null)
    {
        $model = is_string($model) ? $model : get_class($model);
        $name = $this->getName($model, $subtype);
        abort_unless(isset($this->types[$name]), 500, "Category Manager: Unregistered category type for $model!");

        return $this->types[$name];
    }

    /**
     * @return array
     */
    public function titles()
    {
        return $this->titles;
    }

    /**
     * Chuyển model class name thành category type name,
     * Ex: Minhbang\Article\Article => minhbang-article-article-{subtype?}
     *
     * @param string $class
     * @param string $subtype
     *
     * @return string
     */
    public function getName($class, $subtype = null)
    {
        return strtolower(str_replace(['_', '\\', '.'], '-', $class)) . ($subtype ? "-{$subtype}" : '');
    }
}