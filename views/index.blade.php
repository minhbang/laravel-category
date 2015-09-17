@extends('backend.layouts.main')
@section('content')
<div class="panel panel-default panel-nestable panel-sidebar">
    <div class="panel-heading clearfix">
        <div class="loading hidden"></div>
        <a href="{{route('backend.category.create')}}"
           class="modal-link btn btn-success btn-xs"
           data-title="{{trans('common.create_object', array('name' => trans('category::common.item')))}}"
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
        <div class="row">
            <div class="col-xs-9">
                <div class="panel-body-content left">
                    <div id="nestable-container" class="dd">{!! $nestable !!}</div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="panel-body-sidebar right">
                    <ul class="nav nav-tabs tabs-right">
                    @foreach($types as $type => $title)
                        <li{!! $current ==$type ? ' class="active"':'' !!}>
                            <a href="{{route('backend.category.type', ['type' =>$type])}}">{{$title}}</a>
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

@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('.panel-nestable').mbNestable({
            url: {
                data: '{{route('backend.category.data')}}',
                move: '{{route('backend.category.move')}}',
                delete: '{{route('backend.category.destroy', ['category' => '__ID__'])}}'
            },
            max_depth:{{ $max_depth }},
            trans: {
                name: '{{ trans('category::common.item') }}'
            },
            csrf_token: '{{ csrf_token() }}'
        });
        $.fn.mbHelpers.reloadPage = function () {
            $('.panel-nestable').mbNestable('reload');
        }
    });
</script>
@stop