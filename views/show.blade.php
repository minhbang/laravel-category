@extends('backend.layouts.modal')
@section('content')
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>ID</td>
            <td><strong>{{$menu->id}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('menu::common.title') }}</td>
            <td><strong>{{$menu->title}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('menu::common.slug') }}</td>
            <td><strong>{{$menu->slug}}</strong></td>
        </tr>
    </table>
@stop