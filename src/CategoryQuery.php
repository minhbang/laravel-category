<?php
namespace Minhbang\LaravelCategory;

/**
 * Class CategoryQuery
 *
 * @package Minhbang\LaravelCategory
 * @property-read string $table
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo belongsTo($related, $foreignKey = null, $otherKey = null, $relation = null)
 */
trait CategoryQuery
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('Minhbang\LaravelCategory\CategoryItem');
    }

    /**
     * Tất cả content thuộc $category và con cháu của $category
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param \Minhbang\LaravelCategory\CategoryItem $category
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeCategorized($query, $category = null)
    {
        if (is_null($category)) {
            return $query->with('category');
        }
        $ids = $category->getDescendantsAndSelf()->lists('id')->all();
        return $query->with('category')
            ->whereIn("{$this->table}.category_id", $ids);
    }
}