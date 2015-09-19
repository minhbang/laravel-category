<?php
namespace Minhbang\LaravelCategory;

use Laracasts\Presenter\PresentableTrait;
use Baum\Node;

/**
 * App\CategoryItem
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $title
 * @property string $slug
 * @property-read \Minhbang\LaravelCategory\CategoryItem $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelCategory\CategoryItem[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelCategory\CategoryItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelCategory\CategoryItem whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelCategory\CategoryItem whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelCategory\CategoryItem whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelCategory\CategoryItem whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelCategory\CategoryItem whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelCategory\CategoryItem whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelCategory\CategoryItem whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class CategoryItem extends Node
{
    use PresentableTrait;
    protected $table = 'categories';
    protected $presenter = 'Minhbang\LaravelCategory\CategoryItemPresenter';
    protected $fillable = ['title', 'slug'];
    public $timestamps = false;
}
