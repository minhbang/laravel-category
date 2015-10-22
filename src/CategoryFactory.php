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
     * Get groups list
     *
     * @return array
     */
    public function getGroups()
    {
        $default = config('category.default_type');
        $types = $this->getTypes();
        $groups = $this->groups() + [
                $default => [$default],
            ];
        foreach ($groups as $group => $lists) {
            foreach ($lists as $i => $type) {
                $groups[$group][$i] = $types[$type];
            }
        }
        return $groups;
    }

    /**
     * Get types list
     *
     * @return array
     */
    public function getTypes()
    {
        $default = config('category.default_type');
        return [
            $default => trans("category::type.{$default}"),
        ] + $this->types();
    }

    /**
     * Danh sách types, dạng ['type' => 'type name']
     *
     * @return array
     */
    protected function types()
    {
        return [];
    }

    /**
     * Phân nhóm các types, dạng ['group' => ['type1', 'type2'...]]
     *
     * @return array
     */
    protected function groups()
    {
        return [];
    }
}