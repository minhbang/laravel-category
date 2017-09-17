<?php

namespace Minhbang\Category;

/**
 * Class Categorized
 *
 * @property-read string $category_title
 * @package Minhbang\Category
 * @mixin \Eloquent
 */
trait Categorized
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Tất cả content thuộc $category và con cháu của $category
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Minhbang\Category\Category|array $category
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeCategorized($query, $category = null)
    {
        $query->with('category');
        if ($category instanceof Category) {
            $query->whereIn("{$this->table}.category_id", $category->descendantsAndSelf()->pluck('id')->all());
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeWithCategoryTitle($query)
    {
        return $query->leftJoin('categories', 'categories.id', '=', "{$this->table}.category_id")
            ->addSelect('categories.title as category_title');
    }
}