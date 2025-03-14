@extends($_viewFrame)

@section('pageTitleMain'){{$pageTitle}}@endsection
@section('pageKeywords'){{$pageTitle}}@endsection
@section('pageDescription'){{$pageTitle}}@endsection

@section('body')
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
    <div class="ub-m-container margin-top margin-bottom mars-privacy" style="max-width:90rem;">
        <div class="ub-panel">
            <div class="head"></div>
            <div class="body">
                <div class="ub-article">
                    <h1 class="ub-text-center">{{$pageTitle}}</h1>
                    <div class="attr"></div>
                    <div class="content ub-html lg">
                        {!! $pageContent !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

             var isAppleDevice = /iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

            if (!isAppleDevice) {
                $('<meta name="viewport" content="width=device-width , user-scalable=no" />').appendTo('head');
                function adapter() {
                    const clientWidth = document.documentElement.clientWidth
                    var screenWidth = window.innerWidth;
                    document.querySelector('html').style.fontSize = screenWidth / 100 + 'px'

                }

                console.log(document.documentElement.clientWidth)
                console.log(window.innerWidth)
                adapter()
                // // 监听页面刷新，自动变换根字体大小
                // window.onresize = adapter
            } else {
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
        });
    </script>
@endsection

