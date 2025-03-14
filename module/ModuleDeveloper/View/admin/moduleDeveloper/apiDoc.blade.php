@extends('modstart::admin.dialogFrame')

@section('pageTitle'){{$moduleInfo['title']}} 接口文档@endsection

@section('bodyContent')
    <div style="margin:-0.5rem;">
        <div id="app"></div>
    </div>
@endsection

@section('bodyAppend')
    <script>
        window.__moduleDeveloperApiDocSpec = {!! \ModStart\Core\Util\SerializeUtil::jsonEncode($spec) !!};
    </script>
    <script src="@asset('asset/vendor/vue.js')"></script>
    <script src="@asset('asset/vendor/element-ui/index.js')"></script>
    <script src="@asset('vendor/ModuleDeveloper/entry/apiDoc.js')"></script>
@endsection
