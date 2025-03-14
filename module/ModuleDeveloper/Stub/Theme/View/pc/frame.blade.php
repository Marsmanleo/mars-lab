@extends('theme.default.pc.frame')

@section('htmlProperties')@endsection

@section('headAppend')
    @parent
    <link rel="stylesheet" href="@asset('vendor/$ModuleName$/css/theme.css')" />
@endsection

@section('body')
    @include('theme.default.pc.share.header')
    @section('bodyContent')@show
    @include('theme.default.pc.share.footer')
@endsection
