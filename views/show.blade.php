@extends('kit::backend.layouts.modal')
@section('content')
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>ID</td>
            <td><strong>{{$category->id}}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Title') }}</td>
            <td><strong>{{$category->title}}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Slug') }}</td>
            <td><strong>{{$category->slug}}</strong></td>
        </tr>
    </table>
@stop