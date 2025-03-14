@extends('theme.default.pc.frame')

@section('htmlProperties')@endsection

@section('headAppend')
    @parent
    <link rel="stylesheet" href="@asset('vendor/CmsThemeMars/css/theme-m.css')" />
    <link rel="stylesheet" href="@asset('vendor/CmsThemeMars/css/theme.css')" />
@endsection
<script>
    var isAppleDevice = /iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

    if (isAppleDevice) {

        var docEl = document.documentElement
        var dpr = window.devicePixelRatio || 1

        // adjust body font size
        function setBodyFontSize () {
            if (document.body) {
                document.body.style.fontSize = (12 * dpr) + 'px'
            }
            else {
                document.addEventListener('DOMContentLoaded', setBodyFontSize)
            }
        }
        setBodyFontSize();

        // set 1rem = viewWidth / 100
        function setRemUnit () {
            var rem = docEl.clientWidth / 100
            docEl.style.fontSize = rem + 'px'
        }

        setRemUnit()

        // reset rem unit on page resize
        window.addEventListener('resize', setRemUnit)
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                setRemUnit()
            }
        })

        // detect 0.5px supports
        if (dpr >= 2) {
            var fakeBody = document.createElement('body')
            var testElement = document.createElement('div')
            testElement.style.border = '.5px solid transparent'
            fakeBody.appendChild(testElement)
            docEl.appendChild(fakeBody)
            if (testElement.offsetHeight === 1) {
                docEl.classList.add('hairlines')
            }
            docEl.removeChild(fakeBody)
        }
    }
</script>
@section('body')
    @include('module::CmsThemeMars.View.m.header')
@section('bodyContent')
@show
@include('module::CmsThemeMars.View.m.footer')
@endsection
