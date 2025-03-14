@extends('modstart::admin.dialogFrame')

@section('pageTitle')数据库字典@endsection

@section('headAppend')
    @parent
    <style>
        .ub-html table{
            width:100%;
        }
        .ub-html table th:first-child{
            width: 20%;
        }
        .ub-html table th:nth-child(2){
            width: 30%;
        }
    </style>
@endsection

@section('bodyContent')
    <div class="ub-content-box">
        <div class="ub-html lg">
            {!! $markdownHtml !!}
        </div>
    </div>
@endsection
