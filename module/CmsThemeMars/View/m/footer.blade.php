<footer class="ub-m-footer-link">
    <div class="ub-m-container m-footer-p1">
        <p>加入会员</p>
        <p>购物车</p>
        <p>了解更多</p>
    </div>
    <div class="ub-m-container m-footer-p2">
        <p>需要幫助？</p>
        <div>
            <button>在線客服</button>
            <button>撥打{{modstart_config('Site_ContactPhone','[公司电话]')}}</button>
        </div>
    </div>
    <div class="ub-m-container m-footer-p5">
        <ul class="menu-content">
            @foreach(\Module\Nav\Util\NavUtil::listByPositionWithCache('foot') as $key=>$nav)
            <li>
                <div>{{$nav['name']}} <img  src="@asset('vendor/Site/image/left.png')" alt="" /></div>
                <ul class="list">
                    @foreach($nav['_child'] as $child)
                        <li><a href="{{$child['link']}}" {{\Module\Nav\Type\NavOpenType::getBlankAttributeFromValue(empty($child['openType'])?null:$child['openType'])}}>{{$child['name']}}</a></li>
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="ub-m-container m-footer-p3">
        <p>鏈接</p>
        <div>
            <img src="@asset('vendor/Site/image/facebook-fill.png')" alt="">
            <img src="@asset('vendor/Site/image/wei.png')" alt="">
            <img src="@asset('vendor/Site/image/ins.png')" alt="">
            <img src="{{\ModStart\Core\Assets\AssetsUtil::fix(modstart_config('bannerLogo'))}}" alt="">
        </div>
    </div>

    <div class="ub-m-container m-footer-p4">
        <p>隐私保护 条件条款|产品真伪监别</p>
        <p>MAKE-UP ART COSMETICS.ALL WORLDWDE RIGHTS RESERVED</p>
    </div>


</footer>
<script>
    $(document).ready(function() {

        $(" .menu-content li div").click(function (){
            $(this).next('ul.list').slideToggle();


            if ($(this).find('img').attr('data') == 'open') {

                $(this).find('img').css({
                    'transform': 'rotate(0deg)',
                    '-ms-transform': 'rotate(0deg)',
                    '-webkit-transform': 'rotate(0deg)'
                }) ;
                $(this).find('img').attr('data', 'close');
            } else {

                $(this).find('img').css({
                    'transform': 'rotate(90deg)',
                    '-ms-transform': 'rotate(90deg)',
                    '-webkit-transform': 'rotate(90deg)'
                });
                $(this).find('img').attr('data', 'open');

            }
        });
        $('.toggle-form').click(function () {
            $(this).toggle();
            $(this).siblings('.toggle-form').toggle();
            // $('.ub-form').find('form').toggle();
        });

        $('.mars-login  .nav-m').click(function () {
            // $(this).toggle();
            // $(this).find('.toggle-form').toggle();
            // $('.mars-login').find('form').toggle();
            // if ($(this).css('top') == '0px') {
            //     $(this).css('top', '49rem');
            //     $(this).addClass('nav-m-bg');
            // } else {
            //     $(this).css('top', '0px');
            //     $(this).removeClass('nav-m-bg');
            // }
        });
        $('.mars-register .nav-m').click(function () {
            // $(this).toggle();
            // $(this).find('.toggle-form').toggle();
            // $('.mars-register').find('form').toggle();
            // if ($(this).css('top') == '0px') {
            //     $(this).css('top', '75rem');
            //     $(this).addClass('nav-m-bg');
            // } else {
            //     $(this).css('top', '0px');
            //     $(this).removeClass('nav-m-bg');
            // }
        });

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