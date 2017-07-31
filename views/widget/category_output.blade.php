<?php
/**
 * @var \Minhbang\Layout\Widget $widget
 * @var string $category_tree
 */
?>
<div id="category-tree-{{$widget->id}}"></div>
@push('scripts')
    <script type="text/javascript">
        var category_route_{{$widget->id}} = '{{Route::has($widget->data['route_show']) ? route($widget->data['route_show'], ['category' => '__ID__']) : '#__ID__'}}',
            categories_data_{{$widget->id}} = {!! $category_tree !!};

        $(document).ready(function () {
            var categories_tree = $('#category-tree-{{$widget->id}}');
            categories_tree.treeview({
                data: categories_data_{{$widget->id}},
                levels: 1
            });
            categories_tree.on('click', 'li', function (e) {
                e.preventDefault();
                if ($(e.target).is('.expand-icon')) {
                    return;
                }
                window.location.href = category_route_{{$widget->id}}.replace('__ID__', $(this).data('id'));
            });
        });
    </script>
@endpush