<?php
namespace Minhbang\Category;

use Minhbang\LaravelKit\Extensions\BackendController;
use Minhbang\LaravelKit\Traits\Controller\QuickUpdateActions;
use Request;

class Controller extends BackendController
{
    use QuickUpdateActions;

    protected $moderator = true;

    /**
     * Quản lý category
     *
     * @var \Minhbang\Category\Manager
     */
    protected $manager;

    /**
     * @var string Category type hiện tại
     */
    protected $type;

    /**
     * @var bool
     */
    protected $type_fixed = true;

    /**
     * @var array
     */
    protected $views = [
        'form'  => 'category::form',
        'index' => 'category::index',
        'show'  => 'category::show',
    ];

    public function __construct()
    {
        parent::__construct();

        if (is_null($this->type)) {
            $this->switchType();
            $this->type_fixed = false;
        } else {
            $this->manager = app('category')->manage($this->type);
        }
    }

    /**
     * @param null|string $type
     */
    protected function switchType($type = null)
    {
        $key = 'backend.category.type';
        $type = $type ?: session($key, config('category.default_type'));
        session([$key => $type]);
        $this->manager = app('category')->manage($type);
        $this->type = $type;
    }

    /**
     * @param string|null $type
     *
     * @return \Illuminate\View\View
     */
    public function index($type = null)
    {
        if (!$this->type_fixed) {
            $this->switchType($type);
        }
        $max_depth = $this->manager->max_depth;
        $nestable = $this->manager->nestable();
        $types = $this->manager->typeNames();
        $current = $this->type;
        $user_groups = app('user-manager')->listGroups();
        $this->buildHeading(
            [trans('category::common.manage'), "[{$types[$current]}]"],
            'fa-sitemap',
            ['#' => trans('category::common.category')]
        );
        $use_moderator = Item::$use_moderator;

        return view(
            $this->views['index'],
            compact('max_depth', 'nestable', 'types', 'current', 'user_groups', 'use_moderator')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->_create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Minhbang\Category\Item $category
     *
     * @return \Illuminate\View\View
     */
    public function createChildOf(Item $category)
    {
        return $this->_create($category);
    }

    /**
     * @param null|\Minhbang\Category\Item $parent
     *
     * @return \Illuminate\View\View
     */
    protected function _create($parent = null)
    {
        if ($parent) {
            $parent_title = $parent->title;
            $url = route('backend.category.storeChildOf', ['category' => $parent->id]);
        } else {
            $parent_title = '- ROOT -';
            $url = route('backend.category.store');
        }
        $category = new Item();
        $method = 'post';

        return view(
            $this->views['index'],
            compact('parent_title', 'url', 'method', 'category')
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Category\ItemRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(ItemRequest $request)
    {
        return $this->_store($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Category\ItemRequest $request
     * @param \Minhbang\Category\Item $category
     *
     * @return \Illuminate\View\View
     */
    public function storeChildOf(ItemRequest $request, Item $category)
    {
        return $this->_store($request, $category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Category\ItemRequest $request
     * @param null|\Minhbang\Category\Item $parent
     *
     * @return \Illuminate\View\View
     */
    public function _store($request, $parent = null)
    {
        $category = new Item();
        $category->fill($request->all());
        $category->save();
        $category->makeChildOf($parent ?: $this->manager->typeRoot());

        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.create_object_success', ['name' => trans('category::common.item')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \Minhbang\Category\Item $category
     *
     * @return \Illuminate\View\View
     */
    public function show(Item $category)
    {
        return view($this->views['show'], compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Minhbang\Category\Item $category
     *
     * @return \Illuminate\View\View
     */
    public function edit(Item $category)
    {
        $parent = $category->parent;
        $parent_title = $parent->isRoot() ? '- ROOT -' : $parent->title;
        $url = route('backend.category.update', ['category' => $category->id]);
        $method = 'put';

        return view($this->views['form'], compact('parent_title', 'url', 'method', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Minhbang\Category\ItemRequest $request
     * @param \Minhbang\Category\Item $category
     *
     * @return \Illuminate\View\View
     */
    public function update(ItemRequest $request, Item $category)
    {
        $category->fill($request->all());
        $category->save();

        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('category::common.item')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Minhbang\Category\Item $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Item $category)
    {
        $category->delete();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('category::common.category')]),
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function data()
    {
        return response()->json(['html' => $this->manager->nestable()]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function move()
    {
        if ($category = $this->getNode('element')) {
            if ($leftNode = $this->getNode('left')) {
                $category->moveToRightOf($leftNode);
            } else {
                if ($rightNode = $this->getNode('right')) {
                    $category->moveToLeftOf($rightNode);
                } else {
                    if ($destNode = $this->getNode('parent')) {
                        $category->makeChildOf($destNode);
                    } else {
                        return $this->dieAjax();
                    }
                }
            }

            return response()->json(
                [
                    'type'    => 'success',
                    'content' => trans('common.order_object_success', ['name' => trans('category::common.item')]),
                ]
            );
        } else {
            return $this->dieAjax();
        }
    }

    /**
     * @param string $name
     *
     * @return null|\Minhbang\Category\Item
     */
    protected function getNode($name)
    {
        $id = Request::input($name);
        if ($id) {
            if ($node = Item::find($id)) {
                return $node;
            } else {
                return $this->dieAjax();
            }
        } else {
            return null;
        }
    }

    /**
     * Kết thúc App, trả về message dạng JSON
     *
     * @return mixed
     */
    protected function dieAjax()
    {
        return die(json_encode(
            [
                'type'    => 'error',
                'content' => trans('category::common.not_found'),
            ]
        ));
    }

    /**
     * Các attributes cho phéo quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes()
    {
        return [
            'moderator_id' => [
                'rules' => 'required|integer',
                'label' => trans('category::common.moderator_id'),
            ],
        ];
    }
}
