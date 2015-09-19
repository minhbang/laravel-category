<?php
namespace Minhbang\LaravelCategory;

/**
 * Class CategoryFactory
 *
 * @package Minhbang\LaravelCategory
 */
class CategoryFactory
{
    /**
     * Get types list
     *
     * @return array
     */
    public function getTypes()
    {
        $default = config('category.default_type');
        return $this->types() + [
            $default => trans("category::type.{$default}"),
        ];
    }

    /** Set custom types, dạng ['type' => 'type name']
     *
     * @return array
     */
    protected function types()
    {
        return [];
    }
}