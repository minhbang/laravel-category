@extends('backend.layouts.modal')
@section('content')
    <ul class="nav nav-tabs nav-tabs-no-boder">
        @foreach($locales as $locale => $lang)
            <li role="presentation" class="{{$locale == $active_locale ? 'active': ''}}">
                <a href="#{{$locale}}-attributes" role="tab" data-toggle="tab">
                    <span class="text-{{LocaleManager::css($locale)}}">{{$lang}}</span>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($locales as $locale => $lang)
            <div role="tabpanel" class="tab-pane{{$locale == $active_locale ? ' active': ''}}"
                 id="{{$locale}}-attributes">
                <table class="table table-hover table-striped table-bordered table-detail">
                    <tr>
                        <td>ID</td>
                        <td><strong>{{$category->id}}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ trans('category::common.title') }}</td>
                        <td><strong>{{ $category->{"title:$locale| "} }}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ trans('category::common.slug') }}</td>
                        <td><strong>{{ $category->{"slug:$locale| "} }}</strong></td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
@stop