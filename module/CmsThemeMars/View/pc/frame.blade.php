@extends('theme.default.pc.frame')

@section('htmlProperties')@endsection

@section('headAppend')
    @parent
    <link rel="stylesheet" href="@asset('vendor/CmsThemeMars/css/theme.css')" />
    <link rel="stylesheet" media="screen and (min-width: 861px)" href="@asset('vendor/CmsThemeMars/css/theme-pc.css')" />
    <link rel="stylesheet" media="screen and (max-width: 860.99px)" href="@asset('vendor/CmsThemeMars/css/theme-m.css')" />
@endsection
<script>
    var ryanWidth = 860;
    function adapter() {
        const clientWidth = document.documentElement.clientWidth
        var screenWidth = window.innerWidth;
        screenWidth =  screenWidth>860 ? 1440 : 430;
        document.querySelector('html').style.fontSize = screenWidth /100 + 'px'
    }
    console.log(window.devicePixelRatio )
    adapter()
    // 监听页面刷新，自动变换根字体大小
    window.onresize = adapter
</script>
@section('body')
    @include('module::CmsThemeMars.View.pc.header')
    @section('bodyContent')
    @show
    @include('module::CmsThemeMars.View.pc.footer')
@endsection
