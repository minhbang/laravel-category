<?php
namespace Minhbang\Category;

use Eloquent;

/**
 * Class CategoryTranslation
 *
 * @package Minhbang\Category
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property integer $category_id
 * @property string $locale
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\CategoryTranslation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\CategoryTranslation whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\CategoryTranslation whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\CategoryTranslation whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\CategoryTranslation whereLocale($value)
 * @mixin \Eloquent
 */
class CategoryTranslation extends Eloquent
{
    public $timestamps = false;
    protected $table = 'category_translations';
    protected $fillable = ['title', 'slug'];
}
