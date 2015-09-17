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
        return $this->types() + [
            'main' => trans('category::type.main'),
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