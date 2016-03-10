@extends('backend.layouts.modal')
@section('content')
    {!! Form::model($category,['class' => 'form-horizontal','url' => $url, 'method' => $method]) !!}
    <div class="form-group">
        <label class="col-xs-3 control-label">{{ trans('category::common.parent') }}</label>
        <div class="col-xs-9">
            <p class="form-control-static text-primary">{{ $parent_title }}</p>
        </div>
    </div>
    <ul class="nav nav-tabs m-b-md">
        @foreach($locales as $locale => $locale_title)
            <li role="presentation" class="{{$locale == $active_locale ? 'active': ''}}">
                <a href="#{{$locale}}-attributes" role="tab" data-toggle="tab">
                    <span class="text-{{LocaleManager::css($locale)}}">{{$locale_title}}</span>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($locales as $locale => $locale_title)
            <div role="tabpanel" class="tab-pane{{$locale == $active_locale ? ' active': ''}}"
                 id="{{$locale}}-attributes">
                <div class="form-group{{ $errors->has('title') ? ' has-error':'' }}">
                    {!! Form::label('label', trans('category::common.title'), ['class' => 'col-xs-3 control-label text-'. LocaleManager::css($locale)]) !!}
                    <div class="col-xs-9">
                        {!! Form::text("{$locale}[title]", $category->{"title:$locale| "}, ['class' => 'has-slug form-control','data-slug_target' => "#{$locale}-slug"]) !!}
                        @if($errors->has('title'))
                            <p class="help-block">{{ $errors->first('title') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('slug') ? ' has-error':'' }}">
                    {!! Form::label('slug', trans('category::common.slug'), ['class' => 'col-xs-3 control-label text-'. LocaleManager::css($locale)]) !!}
                    <div class="col-xs-9">
                        {!! Form::text("{$locale}[slug]", $category->{"slug:$locale| "}, ['class' => 'form-control', 'id' => "{$locale}-slug"]) !!}
                        @if($errors->has('slug'))
                            <p class="help-block">{{ $errors->first('slug') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {!! Form::close() !!}
@stop