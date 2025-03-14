@extends('modstart::admin.frame')

@section('pageTitle')模块开发助手@endsection

@section($_tabSectionName)
    <div id="app"></div>
@endsection

@section('bodyAppend')
    <script src="@asset('asset/vendor/vue.js')"></script>
    <script src="@asset('asset/vendor/element-ui/index.js')"></script>
    <script>
        window.__data = {
            apiBase: '{{\Module\ModuleDeveloper\Util\ModuleDeveloperUtil::REMOTE_BASE}}',
            modstartParam: {
                version: '{{\ModStart\ModStart::$version}}',
                url: window.location.href
            }
        };
    </script>
    <script src="@asset('vendor/ModuleDeveloper/entry/moduleDeveloper.js')"></script>
@endsection

