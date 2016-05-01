<?php
namespace Minhbang\Category\Support;

use CategoryManager;
use Session;

/**
 * Class CategorizedController
 *
 * @package Minhbang\Category
 */
trait CategorizedController
{
    /**
     * @var \Minhbang\Category\Root
     */
    protected $categoryManager;
    /**
     * @var string
     */
    protected $categoryManagerName;
    /**
     * Loại bài viết hiện tại, vd: news, page
     *
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $typeName;
    /**
     * Danh sách các types hợp lệ của article
     *
     * @var string[]
     */
    protected $types;

    protected function bootCategorizedController()
    {
        abort_if(empty($this->types), 500, static::class . ': $types is empty!...');
        abort_if(empty($this->categoryManagerName), 500, static::class . ': $categoryManagerName is empty!...');
        $this->switchCategoryType();
    }

    /**
     * @param null|string $type
     */
    protected function switchCategoryType($type = null)
    {
        $key = static::class;
        $type = $type ?: session($key, current($this->types));
        if (in_array($type, $this->types)) {
            session([$key => $type]);
            $this->categoryManager = CategoryManager::root($this->categoryManagerName, $type);
            $this->type = $type;
            $this->typeName = $this->categoryManager->type();
        } else {
            Session::forget($key);
            abort(404, trans('category::common.invalid_type'));
        }
    }
}