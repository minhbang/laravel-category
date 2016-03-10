<?php
namespace Minhbang\Category;

/**
 * Class Categorized
 *
 * @package Minhbang\Category
 * @property-read string $table
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo belongsTo($related, $foreignKey = null, $otherKey = null, $relation = null)
 */
trait Categorized
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('Minhbang\Category\Category');
    }

    /**
     * Tất cả content thuộc $category và con cháu của $category
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param \Minhbang\Category\Category $category
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeCategorized($query, $category = null)
    {
        if (is_null($category)) {
            return $query->with('category');
        }
        $ids = $category->descendantsAndSelf()->pluck('id')->all();

        return $query->with('category')->whereIn("{$this->table}.category_id", $ids);
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