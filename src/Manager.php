<?php

namespace Minhbang\Category;

use Illuminate\Support\Collection;
use Kit;
use Schema;

/**
 * Class Manager
 *
 * @package Minhbang\Category
 */
class Manager extends Collection
{
    /**
     * @var \Minhbang\Category\Root[]
     */
    protected $roots = [];

    /**
     * @var array
     */
    protected $types = [];

    /** @var  \Minhbang\Category\Category */
    protected $current;

    /**
     * @param null|int|string|\Minhbang\Category\Category $category
     * @return \Minhbang\Category\Category
     */
    public function current($category = null)
    {
        if ($category === false) {
            return ($this->current = null);
        }
        if (is_a($category, Category::class)) {
            return ($this->current = $category);
        }
        if (is_string($category)) {
            return ($this->current = Category::findBySlug($category));
        }
        if (is_numeric($category)) {
            return ($this->current = Category::find((int) $category));
        }

        return $this->current;
    }

    /**
     * @param string|mixed $model
     *
     * @param string $sub
     * @return \Minhbang\Category\Root
     */
    public function of($model, $sub = null)
    {
        return $this->root($this->getAlias($model, $sub));
    }

    /**
     * Lấy root của category $type
     *
     * @param string $type
     *
     * @return \Minhbang\Category\Root
     */
    public function root($type)
    {
        abort_unless($this->has($type), 404, __('Invalid Category type!'));

        return $this->get($type)['root'];
    }

    /**
     * @param string $type
     * @param mixed $default
     *
     * @return array|string
     */
    public function typeNames($type = null, $default = false)
    {
        return array_get($this->mapWithKeys(function ($item) {
            return [$item['alias'] => $item['title']];
        }), $type, $default);
    }

    /**
     * Đăng ký một model có sử dụng category
     * $sub: ví dụ news: Bài viết dạng Tin tức, message: Bài viết dạng thông báo...
     *
     * @param string|mixed $model
     * @param string $sub
     * @param string $sub_title
     */
    public function register($model, $sub = null, $sub_title = null)
    {
        if (Schema::hasTable('categories')) {
            $alias = $this->getAlias($model, $sub);
            $this->put($alias, [
                'alias' => $alias,
                'title' => Kit::title($model).($sub_title ? " - $sub_title" : ''),
                'root' => new Root($alias, config('category.max_depth')),
            ]);
        }
    }

    /**
     * @param string $attribute
     *
     * @return array|mixed
     */
    public function firstType($attribute = null)
    {
        return array_get($this->first(), $attribute);
    }

    /**
     * @param string|mixed $model
     * @param null $sub
     * @return string
     */
    protected function getAlias($model, $sub = null)
    {
        return Kit::alias($model).($sub ? "_$sub" : '');
    }
}