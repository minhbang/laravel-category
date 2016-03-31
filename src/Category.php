<?php
namespace Minhbang\Category;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\Kit\Extensions\NestedSetModel;
use Minhbang\Locale\Translatable;
use LocaleManager;

/**
 * Class Category
 *
 * @package Minhbang\Category
 * @property integer $id
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $moderator_id
 * @property string $title
 * @property string $slug
 * @property-read mixed $url
 * @property-read \Minhbang\Category\Category $parent
 * @property-read \Baum\Extensions\Eloquent\Collection|\Minhbang\Category\Category[] $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Category\CategoryTranslation[] $translations
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category whereModeratorId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Category\Category slug($slug, $locale = null)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 * @mixin \Eloquent
 */
class Category extends NestedSetModel
{
    use PresentableTrait;
    use Translatable {
        save as traitsave;
    }

    protected $table = 'categories';
    protected $presenter;
    protected $fillable = ['title', 'slug', 'moderator_id'];
    protected $translatable = ['title', 'slug'];
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
        return route('category.show', ['slug' => $this->slug]);
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     * @param string $slug
     * @param string $locale
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeSlug($query, $slug, $locale = null)
    {
        return $query->leftJoin('category_translations', 'category_translations.category_id', '=', "{$this->table}.id")
            ->where('slug', $slug)->where('locale', LocaleManager::getLocale($locale));
    }

    /**
     * @param string $slug
     * @param string $locale
     *
     * @return null|static
     */
    public static function findBySlug($slug, $locale = null)
    {
        return static::slug($slug, $locale)->first();
    }

    /**
     * @param string $slug
     *
     * @return \Minhbang\Category\Category|null|static
     */
    public static function findRootBySlugOrCreate($slug)
    {
        $fallback = LocaleManager::getFallback();
        if ($instance = static::findBySlug($slug, $fallback)) {
            return $instance;
        } else {
            $items = [];
            foreach (LocaleManager::all(true) as $locale) {
                $items[$locale] = ['title' => $slug, 'slug' => $slug];
            }

            return static::create($items);
        }
    }

    /**
     * Khắc phục tạm thời lỗi không tương thích với Baum\Node
     *
     * @see https://github.com/dimsav/laravel-translatable/issues/25#issuecomment-47740434
     *
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        $tempTranslations = $this->translations;
        if ($this->exists) {
            if (count($this->getDirty()) > 0) {
                // If $this->exists and dirty, parent::save() has to return true. If not,
                // an error has occurred. Therefore we shouldn't save the translations.
                if (parent::save($options)) {
                    $this->translations = $tempTranslations;

                    return $this->saveTranslations();
                }

                return false;
            } else {
                // If $this->exists and not dirty, parent::save() skips saving and returns
                // false. So we have to save the translations
                $this->translations = $tempTranslations;

                return $this->saveTranslations();
            }
        } elseif (parent::save($options)) {
            // We save the translations only if the instance is saved in the database.
            $this->translations = $tempTranslations;

            return $this->saveTranslations();
        }

        return false;
    }
}
