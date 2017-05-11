@extends($layout)
@section('content')
    <div class="panel panel-default panel-nestable panel-sidebar">
        <div class="panel-heading clearfix">
            <div class="loading hidden"></div>
            <a href="{{route($route_prefix. 'backend.category.create')}}"
               class="modal-link btn btn-success btn-xs"
               data-title="{{trans('common.create_object', ['name' => trans('category::common.item')])}}"
               data-label="{{trans('common.save')}}"
               data-icon="align-justify">
                <span class="glyphicon glyphicon-plus-sign"></span> {{trans('category::common.create_item')}}
            </a>
            <a href="#" data-action="collapseAll" class="nestable_action btn btn-default btn-xs">
                <span class="glyphicon glyphicon-circle-arrow-up"></span>
            </a>
            <a href="#" data-action="expandAll" class="nestable_action btn btn-default btn-xs">
                <span class="glyphicon glyphicon-circle-arrow-down"></span>
            </a>
        </div>
        <div class="panel-body">
            <div class="row row-height">
                <div class="row-height-inside">
                    <div class="col-xs-9 col-height">
                        <div class="panel-body-content left">
                            <div class="dd-category">
                                <div class="nested-list-head">
                                    <div class="nested-list-actions nested-list-titles pull-right">
                                        {{ $use_moderator ? trans('category::common.moderator_id'):'' }}
                                        <div class="actions">{{trans('common.actions')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div id="nestable-container" class="dd dd-category">{!! $nestable !!}</div>
                        </div>
                    </div>
                    <div class="col-xs-3 col-height panel-body-sidebar right">
                        <ul class="nav nav-tabs tabs-right">
                            @foreach($types as $type => $title)
                                <li{!! $current ==$type ? ' class="active"':'' !!}>
                                    <a href="{{route($route_prefix. 'backend.category.type', ['type' =>$type])}}">{{$title}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <div class="panel-footer">
            <span class="glyphicon glyphicon-info-sign"></span> {{ trans('category::common.order_hint')}}
        </div>
    </div>
@stop

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.panel-nestable').mbNestable({
            url: {
                data: '{{route($route_prefix. 'backend.category.data')}}',
                move: '{{route($route_prefix. 'backend.category.move')}}',
                delete: '{{route($route_prefix. 'backend.category.destroy', ['category' => '__ID__'])}}'
            },
            max_depth:{{ $max_depth }},
            trans: {
                name: '{{ trans('category::common.item') }}'
            },
            csrf_token: window.Laravel.csrfToken,
            afterDrop: function () {
                location.reload(true);
            }
        });
        $.fn.mbHelpers.reloadPage = function () {
            $('.panel-nestable').mbNestable('reload');
        };
        $('.dd a.quick-update').quickUpdate({
            url: '{{ route($route_prefix. 'backend.category.quick_update', ['category' => '__ID__']) }}',
            container: '.panel-nestable',
            elementTemplate: '{!! Form::select('_value', $user_groups, null, ['class' => '_value form-control']) !!}',
            afterShow: function (element, form) {
                $('select._value', form).selectize();
            },
            processResult: function () {
                location.reload(true);
            }
        });
    });
</script>
@endpush