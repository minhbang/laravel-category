<?php

namespace Minhbang\Category;

use Laracasts\Presenter\Presenter;
use Html;
use Minhbang\Kit\Traits\Presenter\NestablePresenter;
use CategoryManager;

/**
 * Class CategoryPresenter
 *
 * @property-read \Minhbang\Category\Category $entity
 * @package Minhbang\Category
 */
class CategoryPresenter extends Presenter
{
    use NestablePresenter;

    /**
     * Tạo tree data cho bootstrap treeview
     *
     * @param \Minhbang\Category\Category|mixed|null $selected
     * @param int $max_depth
     *
     * @return string
     */
    public function tree($selected = null, $max_depth = null)
    {
        $selected = $selected ?: CategoryManager::current();

        return $this->toTree($this->entity, $selected, $max_depth);
    }

    /**
     * @return string
     */
    public function label()
    {
        return $this->entity->title;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function moderator($name = 'short_name')
    {
        return $this->entity->moderator ? $this->entity->moderator->$name : null;
    }

    /**
     * @param int $max_depth
     *
     * @param string $route_prefix
     *
     * @return string
     */
    public function actions($max_depth, $route_prefix = '')
    {
        if (Category::$use_moderator && $this->entity->depth == 1) {
            $moderator = Html::linkQuickUpdate($this->entity->id, $this->entity->moderator_id, [
                'attr' => 'moderator_id',
                'title' => __('Moderator'),
                'placement' => 'top',
                'class' => 'w-md',
                'label' => $this->moderator(),
                'null_label' => __('— No moderator —'),
                'null_class' => 'text-danger',
            ], ['icon' => 'fa-users', 'size' => 'xs', 'type' => 'warning']);
        } else {
            $moderator = '';
        }

        if ($this->entity->depth < $max_depth) {
            $child = '<a href="'.route("{$route_prefix}backend.category.createChildOf", ['category' => $this->entity->id]).'"
               class="modal-link btn btn-primary btn-xs"
               data-toggle="tooltip"
               data-title="'.__('Create child :name', ['name' => __('Category')]).'"
               data-label="'.__('Save').'"
               data-icon="align-justify"><span class="glyphicon glyphicon-plus"></span>
            </a>';
        } else {
            $child = '<a href="#"
               class="btn btn-primary btn-xs disabled"
               data-toggle="tooltip"
               data-title="'.__('Create child :name', ['name' => __('Category')]).'">
                <span class="glyphicon glyphicon-plus"></span>
            </a>';
        }

        $show = '<a href="'.route("{$route_prefix}backend.category.show", ['category' => $this->entity->id]).'"
           data-toggle="tooltip"
           class="modal-link btn btn-success btn-xs"
           data-title="'.__('Details of :name', ['name' => __('Category')]).'"
           data-icon="align-justify"><span class="glyphicon glyphicon-list"></span>
        </a>';
        $edit = '<a href="'.route("{$route_prefix}backend.category.edit", ['category' => $this->entity->id]).'"
           data-toggle="tooltip"
           class="modal-link btn btn-info btn-xs"
           data-title="'.__('Update :name', ['name' => __('Category')]).'"
           data-label="'.__('Save Shanges').'"
           data-icon="align-justify"><span class="glyphicon glyphicon-edit"></span>
        </a>';
        $delete = '<a href="#"
            data-toggle="tooltip"
            data-title="'.__('Delete :name', ['name' => __('Category')]).'"
            data-item_id="'.$this->entity->id.'"
            data-item_title="'.$this->entity->title.'"
            class="delete_item btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
        </a>';

        return $moderator.'<div class="actions">'.$child.$show.$edit.$delete.'</div>';
    }
}