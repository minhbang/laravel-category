<?php namespace Minhbang\Category\Widgets;

use Minhbang\Kit\Support\HasRouteAttribute;
use Minhbang\Layout\WidgetTypes\WidgetType;
use CategoryManager;

/**
 * Class CategoryWidget
 *
 * @package Minhbang\Category\Widgets
 */
class CategoryWidget extends WidgetType {
    use HasRouteAttribute;
    /**
     * @param \Minhbang\Layout\Widget|string $widget
     *
     * @return string
     */
    public function titleBackend( $widget ) {
        $title = is_string( $widget ) ? $widget : ( $widget->data['category_type'] ? CategoryManager::typeNames( $widget->data['category_type'] ) : null );

        return parent::titleBackend( $title ?: $widget );
    }

    /**
     * @return array
     */
    public function getCategoryTypes() {
        return CategoryManager::typeNames();
    }

    /**
     * @return array
     */
    public function formOptions() {
        return [ 'width' => null ] + parent::formOptions();
    }

    /**
     * @return string
     */
    protected function formView() {
        return 'category::widget.category_form';
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function getCategoryTree( $widget ) {
        return $widget->data['category_type'] ? CategoryManager::of( $widget->data['category_type'] )->tree( null, $widget->data['max_depth'] ) : '';
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function content( $widget ) {
        $category_tree = $this->getCategoryTree( $widget );

        return view( 'category::widget.category_output', compact( 'widget', 'category_tree' ) )->render();
    }

    /**
     * @return array
     */
    protected function dataAttributes() {
        return [
            [ 'name' => 'category_type', 'title' => trans( 'category::widget.category.category_type' ), 'rule' => 'required|max:255', 'default' => null ],
            [ 'name' => 'route_show', 'title' => trans( 'category::widget.category.route_show' ), 'rule' => 'required|max:255', 'default' => '' ],
            [ 'name' => 'max_depth', 'title' => trans( 'category::widget.category.max_depth' ), 'rule' => 'required|integer|min:1', 'default' => 1 ],
        ];
    }
}