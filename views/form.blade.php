@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($category,['class' => 'form-horizontal','url' => $url, 'method' => $method]) !!}
    <div class="form-group">
        <label class="col-xs-3 control-label">{{ trans('category::common.parent') }}</label>
        <div class="col-xs-9">
            <p class="form-control-static text-primary">{{ $parent_title }}</p>
        </div>
    </div>
    <div class="form-group{{ $errors->has('title') ? ' has-error':'' }}">
        {!! Form::label('label', trans('category::common.title'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('title', null, ['class' => 'has-slug form-control','data-slug_target' => "#slug"]) !!}
            @if($errors->has('title'))
                <p class="help-block">{{ $errors->first('title') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('slug') ? ' has-error':'' }}">
        {!! Form::label('slug', trans('category::common.slug'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('slug', null, ['class' => 'form-control', 'id' => 'slug']) !!}
            @if($errors->has('slug'))
                <p class="help-block">{{ $errors->first('slug') }}</p>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@stop