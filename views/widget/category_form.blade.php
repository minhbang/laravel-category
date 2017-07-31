<?php /**
 * @var \Minhbang\Layout\Widget $widget
 * @var \Illuminate\Support\MessageBag $errors
 */ ?>
@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($data,['class' => 'form-modal','url' => $url, 'method' => 'put']) !!}
    <div class="row">
        <div class="col-xs-8">
            <div class="form-group {{ $errors->has("category_type") ? ' has-error':'' }}">
                {!! Form::label("category_type", $labels['category_type'], ['class' => "control-label"]) !!}
                {!! Form::select('category_type', $widget->typeInstance()->getCategoryTypes(), null, ['prompt' =>trans( 'category::common.select_category_type' ), 'class' => 'form-control selectize']) !!}
                @if($errors->has('category_type'))
                    <p class="help-block">{{ $errors->first('category_type') }}</p>
                @endif
            </div>
            <div class="form-group {{ $errors->has("route_show") ? ' has-error':'' }}">
                {!! Form::label("route_show", $labels['route_show'], ['class' => "control-label"]) !!}
                {!! Form::select(
                    "route_show", $widget->typeInstance()->getRoutes(), null, ['prompt' => trans('layout::common.select_route'), 'class' => 'form-control selectize'])
                !!}
                @if($errors->has('route_show'))
                    <p class="help-block">{{ $errors->first('route_show') }}</p>
                @endif
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group{{ $errors->has('max_depth') ? ' has-error':'' }}">
                {!! Form::label('label', $labels['max_depth'], ['class' => 'control-label']) !!}
                {!! Form::text('max_depth', null, ['class' => 'form-control']) !!}
                @if($errors->has('max_depth'))
                    <p class="help-block">{{ $errors->first('max_depth') }}</p>
                @endif
            </div>
        </div>
    </div>
    @include('layout::widgets._common_fields')
    {!! Form::close() !!}
@stop