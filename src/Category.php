<?php
namespace Minhbang\Category;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\Kit\Extensions\NestedSetModel;
use CategoryManager;

/**
 * App\Category
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
 * @property-read \Minhbang\Category\Category $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Category[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category slug($slug)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 * @mixin \Eloquent
 */
class Category extends NestedSetModel
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
        $this->presenter = config('category.presenter', CategoryPresenter::class);
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
        return route('category.show', ['category' => $this->id, 'slug' => $this->slug]);
    }

    /**
     * @param bool $self
     * @param bool $index
     *
     * @return array
     */
    public function getBreadcrumbs($self = false, $index = false)
    {
        /** @var static[] $categories */
        $categories = $this->getRoot1Path(['id', 'title', 'slug'], $self);
        $breadcrumbs = $index ? [route('category.index') => trans('category::common.category')] : [];
        foreach ($categories as $category) {
            $breadcrumbs[$category->getUrlAttribute()] = $category->title;
        }
        if (!$self) {
            $breadcrumbs['#'] = $this->title;
        }

        return $breadcrumbs;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $slug
     *
     * @return \Illuminate\Database\Query\Builder
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
        return static::where('slug', $slug)->first();
    }

    /**
     * @param string $slug
     *
     * @return \Minhbang\Category\Category
     */
    public static function findRootBySlugOrCreate($slug)
    {
        if ($instance = static::findBySlug($slug)) {
            return $instance;
        } else {
            return static::create(['title' => $slug, 'slug' => $slug]);
        }
    }

    /**
     * @param string $class
     *
     * @return \Minhbang\Category\Category
     */
    public static function findRootByClass($class)
    {
        return static::findRootBySlugOrCreate(CategoryManager::getName($class));
    }
}
