<?php
namespace Minhbang\Category;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\Kit\Extensions\NestedSetModel;

/**
 * App\Item
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $title
 * @property string $slug
 * @property integer $moderator_id
 * @property-read string $url
 * @property-read \Minhbang\Category\Item $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Item[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Item whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Item whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Item whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Item whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Item whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Item whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Item whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Item whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Item slug($slug)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class Item extends NestedSetModel
{
    use PresentableTrait;
    protected $table = 'categories';
    protected $presenter;
    protected $fillable = ['title', 'slug', 'moderator_id'];
    public $timestamps = false;

    /**
     * @var bool
     */
    public static $use_moderator = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        static::$use_moderator = config('category.use_moderator', true);
        $this->presenter = config('category.presenter', 'Minhbang\Category\ItemPresenter');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moderator()
    {
        return static::$use_moderator ? $this->belongsTo('Minhbang\User\Group') : null;
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('category.show', ['slug' => $this->slug]);
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     * @param string $slug
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * @param string $slug
     *
     * @return static|null
     */
    public static function findBySlug($slug)
    {
        return static::slug($slug)->first();
    }
}
