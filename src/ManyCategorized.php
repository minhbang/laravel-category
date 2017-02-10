<?php
namespace Minhbang\Category;

/**
 * Class ManyCategorized
 *
 * @package Minhbang\Category
 * @mixin \Eloquent
 */
trait ManyCategorized
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * @param array $ids Category IDs
     */
    public function fillCategories($ids)
    {
        if ($ids) {
            if ($this->exists) {
                $this->categories()->sync($ids);
            } else {
                $this->categories()->attach($ids);
            }
        }
    }

    /**
     * Tất cả content thuộc $category và con cháu của $category
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param \Minhbang\Category\Category $category
     * @param bool $immediate
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeCategorized($query, $category, $immediate = false)
    {
        return $category->belongsToManyNestedSet(static::class, null, null, null, null, $query, $immediate);
    }
}