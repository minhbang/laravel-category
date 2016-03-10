<?php
namespace Minhbang\Category;

use LocaleManager;

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

        return $query->with('category')
            ->whereIn("{$this->table}.category_id", $ids);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $locale
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeWithCategoryTitle($query, $locale = null)
    {
        return $query->leftJoin(
            'category_translations',
            function ($join) use ($locale) {
                $join->on('category_translations.category_id', '=', "{$this->table}.category_id")
                    ->where('category_translations.locale', '=', LocaleManager::getLocale($locale));
            }
        )->addSelect('category_translations.title as category_title');
    }
}