@extends('theme.default.pc.frame')

@section('htmlProperties')@endsection

@section('headAppend')
    @parent
    <link rel="stylesheet" href="@asset('vendor/CmsThemeDemo/css/theme.css')" />
@endsection

@section('bodyAppend')
    @parent
    <script src="@asset('vendor/CmsThemeDemo/js/theme.js')"></script>
@endsection

@section('body')
    @include('module::CmsThemeDemo.View.pc.header')
    @section('bodyContent')@show
    @include('module::CmsThemeDemo.View.pc.footer')
@endsection
