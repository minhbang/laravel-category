<?php namespace Minhbang\Category;

use Minhbang\Kit\Extensions\BackendController;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Request;
use CategoryManager;

/**
 * Class Controller
 *
 * @package Minhbang\Category
 */
class Controller extends BackendController {
    use QuickUpdateActions;

    protected $moderator = true;

    /**
     * @var string Category type hiện tại
     */
    protected $type;
    /**
     * @var \Minhbang\Category\Root
     */
    protected $manager;

    /**
     * @var bool
     */
    protected $type_changeable = true;

    /**
     * @var array
     */
    protected $views = [
        'form'  => 'category::form',
        'index' => 'category::index',
        'show'  => 'category::show',
        'empty' => 'category::empty',
    ];

    public function __construct() {
        parent::__construct();
        $this->type_changeable = is_null( $this->type );
    }

    /**
     * @return \Minhbang\Category\Root
     */
    protected function getManager() {
        if ( is_null( $this->manager ) ) {
            if ( $this->type_changeable ) {
                $this->type = session( 'backend.category.type', CategoryManager::firstType( 'alias' ) );
            }
            abort_unless( CategoryManager::has( $this->type ), 404, __('Invalid Category type!' ) );

            $this->manager = CategoryManager::root( $this->type );
        }

        return $this->manager;
    }

    /**
     * @param null|string $type
     */
    protected function switchType( $type = null ) {
        if ( $this->type_changeable ) {
            $this->type = $type ?: CategoryManager::firstType( 'alias' );
            session( [ 'backend.category.type' => $this->type ] );
        }
    }

    /**
     * @param string|null $type
     *
     * @return \Illuminate\View\View
     */
    public function index( $type = null ) {
        if ( CategoryManager::isEmpty() ) {
            $this->buildHeading( __('Manage Category' ), 'fa-sitemap',
                [ '#' => __('Category' ) ]
            );

            return view( $this->views['empty'] );
        }

        $this->switchType( $type );
        $max_depth = $this->getManager()->max_depth;
        $nestable = $this->getManager()->nestable($this->route_prefix);
        $types = $this->getManager()->typeNames();
        $current = $this->type;
        $use_moderator = Category::$use_moderator;
        $user_groups = $use_moderator ? app( 'user-manager' )->listGroups() : [];
        $this->buildHeading(
            [ __('Manage Category' ), "[{$types[$current]}]" ],
            'fa-sitemap',
            [ '#' => __('Category' ) ]
        );


        return view(
            $this->views['index'],
            compact( 'max_depth', 'nestable', 'types', 'current', 'user_groups', 'use_moderator' )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return $this->_create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Minhbang\Category\Category $category
     *
     * @return \Illuminate\View\View
     */
    public function createChildOf( Category $category ) {
        return $this->_create( $category );
    }

    /**
     * @param null|\Minhbang\Category\Category $parent
     *
     * @return \Illuminate\View\View
     */
    protected function _create( $parent = null ) {
        if ( $parent ) {
            $parent_title = $parent->title;
            $url = route( $this->route_prefix . 'backend.category.storeChildOf', [ 'category' => $parent->id ] );
        } else {
            $parent_title = '- ROOT -';
            $url = route( $this->route_prefix . 'backend.category.store' );
        }
        $category = new Category();
        $method = 'post';

        return view(
            $this->views['form'],
            compact( 'parent_title', 'url', 'method', 'category' )
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Category\CategoryRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store( CategoryRequest $request ) {
        return $this->_store( $request );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Category\CategoryRequest $request
     * @param \Minhbang\Category\Category $category
     *
     * @return \Illuminate\View\View
     */
    public function storeChildOf( CategoryRequest $request, Category $category ) {
        return $this->_store( $request, $category );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Category\CategoryRequest $request
     * @param null|\Minhbang\Category\Category $parent
     *
     * @return \Illuminate\View\View
     */
    public function _store( $request, $parent = null ) {
        $category = new Category();
        $category->fill( $request->all() );
        $category->save();
        $category->makeChildOf( $parent ?: $this->getManager()->node() );

        return view(
            'kit::_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => __('Create new <strong>:name</strong> success', [ 'name' => __('Category' ) ] ),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \Minhbang\Category\Category $category
     *
     * @return \Illuminate\View\View
     */
    public function show( Category $category ) {
        return view( $this->views['show'], compact( 'category' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Minhbang\Category\Category $category
     *
     * @return \Illuminate\View\View
     */
    public function edit( Category $category ) {
        $parent = $category->parent;
        $parent_title = $parent->isRoot() ? '- ROOT -' : $parent->title;
        $url = route( $this->route_prefix . 'backend.category.update', [ 'category' => $category->id ] );
        $method = 'put';

        return view( $this->views['form'], compact( 'parent_title', 'url', 'method', 'category' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Minhbang\Category\CategoryRequest $request
     * @param \Minhbang\Category\Category $category
     *
     * @return \Illuminate\View\View
     */
    public function update( CategoryRequest $request, Category $category ) {
        $category->fill( $request->all() );
        $category->save();

        return view(
            'kit::_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => __('Update <strong>:name</strong> success', [ 'name' => __('Category' ) ] ),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Minhbang\Category\Category $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy( Category $category ) {
        $category->delete();

        return response()->json(
            [
                'type'    => 'success',
                'content' => __('Delete <strong>:name</strong> success', [ 'name' => __('Category' ) ] ),
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function data() {
        return response()->json( [ 'html' => $this->getManager()->nestable($this->route_prefix) ] );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function move() {
        if ( $category = $this->getNode( 'element' ) ) {
            if ( $leftNode = $this->getNode( 'left' ) ) {
                $category->moveToRightOf( $leftNode );
            } else {
                if ( $rightNode = $this->getNode( 'right' ) ) {
                    $category->moveToLeftOf( $rightNode );
                } else {
                    if ( $destNode = $this->getNode( 'parent' ) ) {
                        $category->makeChildOf( $destNode );
                    } else {
                        return $this->dieAjax();
                    }
                }
            }

            return response()->json(
                [
                    'type'    => 'success',
                    'content' => __('Update <strong>:name</strong> order success', [ 'name' => __('Category' ) ] ),
                ]
            );
        } else {
            return $this->dieAjax();
        }
    }

    /**
     * @param string $name
     *
     * @return null|\Minhbang\Category\Category
     */
    protected function getNode( $name ) {
        $id = Request::input( $name );
        if ( $id ) {
            if ( $node = Category::find( $id ) ) {
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
    protected function dieAjax() {
        return die( json_encode(
            [
                'type'    => 'error',
                'content' => __('Category not found.' ),
            ]
        ) );
    }

    /**
     * Các attributes cho phéo quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes() {
        return [
            'moderator_id' => [
                'rules' => 'required|integer',
                'label' => __('Moderator' ),
            ],
        ];
    }
}
