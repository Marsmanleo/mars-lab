<footer class="view-pc ub-footer-link reverse footer2 view-pc">
    <div class="ub-container">
        <div class="row">
            <div class="col-md-6 footer-logo ">
                <div class="link">
                    <div class="title">
                        <a href="{{modstart_web_url('')}}">
                            <img src="{{\ModStart\Core\Assets\AssetsUtil::fix(modstart_config('siteLogoFooter'))}}"/>
                        </a>
                    </div>
                    <div class="list tw-pt-4 tw-text-gray-400">
                        <div class="tw-py-1">
                            产品经理或项目相关人员能更快速地
                        </div>
                        <div class="tw-py-1">
                            <div class="input">
                                <input type="email" name="email" placeholder="输入您的电子邮件"/>
                                <button  type="submit">订阅</button>
                            </div>
                            <div class='text'>产品经理或项目相关人员能更快速地</div>
                        </div>
                         
                    </div>
                </div>
            </div>
            <div class="col-md-6 footer-menu">
                <div class="row">
                    @foreach(\Module\Nav\Util\NavUtil::listByPositionWithCache('foot') as $key=>$nav)
                    <div class="col-3">
                        <div class="link">
                            <div class="title">
                                {{$nav['name']}}
                            </div>
                            <div class="list">
                                @foreach($nav['_child'] as $child)
                                    <a href="{{$child['link']}}" {{\Module\Nav\Type\NavOpenType::getBlankAttributeFromValue(empty($child['openType'])?null:$child['openType'])}}>{{$child['name']}}</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="y-line"></div>
                    </div>
                    @endforeach

                </div>
            </div>
            
        </div>
    </div>
    <div class="tw-p-4 tw-text-center" style="background:#444;">
        <a href="http://beian.miit.gov.cn" target="_blank" class="tw-text-gray-100 hover:tw-text-gray-100">
            {{modstart_config('siteBeian','[网站备案信息]')}}
        </a>
        &copy;{{modstart_config('siteDomain','[网站域名]')}}
        <a href="/member/agreement">用户协议</a>
        <a href="/member/privacy">隐私协议</a>
    </div>
</footer>
<script>
    $(document).ready(function() {

        $('.toggle-form').click(function () {
            $(this).toggle();
            $(this).siblings('.toggle-form').toggle();
            $('.ub-form').find('form').toggle();
        });
    });
</script>
<footer class=" view-m ub-m-footer-link">
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
                    <div>{{$nav['name']}} <img  src="@asset('vendor/Site/image/left.png')" alt="" /><img  src="@asset('vendor/Site/image/buttom.png')" alt="" /></div>
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
        <p><a href="/member/agreement">用户协议</a>
            <a href="/member/privacy">隐私协议</a>|产品真伪监别</p>
        <p>MAKE-UP ART COSMETICS.ALL WORLDWDE RIGHTS RESERVED</p>
    </div>


    <script>
        $(document).ready(function() {
            $(".menu-content li div").click(function (){
                $(this).next('ul.list').slideToggle();
                $(this).find('img').slideToggle();
            });

            $('.view-m .toggle-form').click(function () {
                $(this).toggle();
                $(this).siblings('.toggle-form').toggle();
                // $('.ub-form').find('form').toggle();
            });

            $('.mars-login .view-m .toggle-form').click(function () {
                // $(this).toggle();
                // $(this).siblings('.toggle-form').toggle();
                // $('.ub-form-register').find('form').toggle();
                if ($(this).closest('.nav-m').css('top') == '0px') {
                    $(this).closest('.nav-m').css('top', '49rem');
                    $(this).closest('.nav-m').addClass('nav-m-bg');
                } else {
                    $(this).closest('.nav-m').css('top', '0px');
                    $(this).closest('.nav-m').removeClass('nav-m-bg');
                }
            });
            $('.mars-register .view-m .toggle-form').click(function () {
                // $(this).toggle();
                // $(this).siblings('.toggle-form').toggle();
                // $('.ub-form-register').find('form').toggle();
                if ($(this).closest('.nav-m').css('top') == '0px') {
                    $(this).closest('.nav-m').css('top', '75rem');
                    $(this).closest('.nav-m').addClass('nav-m-bg');
                } else {
                    $(this).closest('.nav-m').css('top', '0px');
                    $(this).closest('.nav-m').removeClass('nav-m-bg');
                }
            });

        });
    </script>
</footer>