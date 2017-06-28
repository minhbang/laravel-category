<?php

namespace Minhbang\Category;

use Laracasts\Presenter\Presenter;
use Html;

/**
 * Class CategoryPresenter
 *
 * @package Minhbang\Category
 */
class CategoryPresenter extends Presenter {
    /**
     * @return string
     */
    public function label() {
        return $this->entity->title;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function moderator( $name = 'short_name' ) {
        return $this->entity->moderator ? $this->entity->moderator->$name : null;
    }

    /**
     * @param int $max_depth
     *
     * @param string $route_prefix
     *
     * @return string
     */
    public function actions( $max_depth, $route_prefix = '' ) {
        if ( Category::$use_moderator && $this->entity->depth == 1 ) {
            $moderator = Html::linkQuickUpdate(
                $this->entity->id,
                $this->entity->moderator_id,
                [
                    'attr'       => 'moderator_id',
                    'title'      => trans( 'category::common.moderator' ),
                    'placement'  => 'top',
                    'class'      => 'w-md',
                    'label'      => $this->moderator(),
                    'null_label' => trans( 'category::common.no_moderator' ),
                    'null_class' => 'text-danger',
                ],
                [ 'icon' => 'fa-users', 'size' => 'xs', 'type' => 'warning' ]
            );
        } else {
            $moderator = '';
        }

        if ( $this->entity->depth < $max_depth ) {
            $child = '<a href="' . route( "{$route_prefix}backend.category.createChildOf", [ 'category' => $this->entity->id ] ) . '"
               class="modal-link btn btn-primary btn-xs"
               data-toggle="tooltip"
               data-title="' . trans( 'common.create_child_object', [ 'name' => trans( 'category::common.item' ) ] ) . '"
               data-label="' . trans( 'common.save' ) . '"
               data-icon="align-justify"><span class="glyphicon glyphicon-plus"></span>
            </a>';
        } else {
            $child = '<a href="#"
               class="btn btn-primary btn-xs disabled"
               data-toggle="tooltip"
               data-title="' . trans( 'common.create_child_object', [ 'name' => trans( 'category::common.item' ) ] ) . '">
                <span class="glyphicon glyphicon-plus"></span>
            </a>';
        }

        $show = '<a href="' . route( "{$route_prefix}backend.category.show", [ 'category' => $this->entity->id ] ) . '"
           data-toggle="tooltip"
           class="modal-link btn btn-success btn-xs"
           data-title="' . trans( 'common.object_details_view', [ 'name' => trans( 'category::common.item' ) ] ) . '"
           data-icon="align-justify"><span class="glyphicon glyphicon-list"></span>
        </a>';
        $edit = '<a href="' . route( "{$route_prefix}backend.category.edit", ['category' => $this->entity->id] ) . '"
           data-toggle="tooltip"
           class="modal-link btn btn-info btn-xs"
           data-title="' . trans( 'common.update_object', [ 'name' => trans( 'category::common.item' ) ] ) . '"
           data-label="' . trans( 'common.save_changes' ) . '"
           data-icon="align-justify"><span class="glyphicon glyphicon-edit"></span>
        </a>';
        $delete = '<a href="#"
            data-toggle="tooltip"
            data-title="' . trans( 'common.delete_object', [ 'name' => trans( 'category::common.item' ) ] ) . '"
            data-item_id="' . $this->entity->id . '"
            data-item_title="' . $this->entity->title . '"
            class="delete_item btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
        </a>';

        return $moderator . '<div class="actions">' . $child . $show . $edit . $delete . '</div>';
    }
}