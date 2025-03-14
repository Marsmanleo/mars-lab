<header class="view-pc ub-header-b ub-header-mars">
    <div class="ub-container  ub-mars-top1">
        <div class="container">
            <div class="mars-logo ">
                <a href="{{modstart_web_url('')}}">
                    <img src="{{\ModStart\Core\Assets\AssetsUtil::fix(modstart_config('siteLogo'))}}"/>
                </a>
            </div>
            <div class="tips ">
                <p>关注我的小红书账号，获取更多关于本命香和个性魅力的秘诀。</p>
            </div>
            <div class="side-div">
                <div class="nav-mask" onclick="MS.header.hide()"></div>
                <div class="nav">
                    <div class="search">
                        <div class="box">
                            <form action="{{modstart_web_url('search')}}" method="get">
                                <input type="text" name="keywords" value="{{empty($keywords)?'':$keywords}}" placeholder="输入关键字"/>
                                <button type="submit">
                                    <img src="@asset('vendor/Site/image/search-head.png')" />
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="menu">
                    <img src="@asset('vendor/Site/image/lang-qh.png')" />
{{--                                <img src="@asset('vendor/Site/image/cart-pc.png')" />--}}
                    @if(\Module\Member\Auth\MemberUser::id())
                        <div class="item">
                            <a href="{{modstart_web_url('member')}}" class="sub-title">
                                <img class="account" src="@asset('vendor/Site/image/acount-login.png')" />
                                {{\Module\Member\Auth\MemberUser::get('username')}}
                            </a>
                            <div class="sub-nav">
                                {!! \Module\Member\Config\MemberNavMenu::render() !!}
                                <a class="sub-nav-item" href="javascript:;"
                                   data-href="{{modstart_web_url('logout')}}" data-confirm="确认退出登录？">
                                    退出登录
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{modstart_web_url('login',['redirect'=>\ModStart\Core\Input\Request::redirectUrl()])}}" rel="nofollow">
                            <img class="account" src="@asset('vendor/Site/image/account-icon.png')" />
                        </a>
                    @endif
                </div>
                <a class="nav-toggle" href="javascript:;" onclick="MS.header.trigger()">
                    <i class="show iconfont icon-list"></i>
                    <i class="close iconfont icon-close"></i>
                </a>
            </div>
        </div>


    </div>
    <div class="ub-container  ub-mars-top2">
{{--        {!! \Module\Nav\View\NavView::position('head') !!}--}}
        <ul>
            @foreach(\Module\Nav\Util\NavUtil::listByPositionWithCache('head') as $key=>$nav)
                <li><a href="{{$nav['link']}}">{{$nav['name']}}</a></li>
            @endforeach
        </ul>
    </div>
</header>
<header class="view-m ub-header-b ub-header-mars">
    <div class="tips">
        <p>关注我的小红书账号，获取更多关于本命香和个性魅力的秘诀。</p>
    </div>
    <div class="ub-container  ub-mars-top1">

        <div class="mars-logo">
            <a href="{{modstart_web_url('')}}">
                <img src="{{\ModStart\Core\Assets\AssetsUtil::fix(modstart_config('siteLogo'))}}"/>
            </a>
        </div>

        <div class="nav-mask" onclick="MS.header.hide()">

        </div>
        <div class="nav">
            {{--            <div class="search">--}}
            {{--                <div class="box">--}}
            {{--                    <form action="{{modstart_web_url('search')}}" method="get">--}}
            {{--                        <input type="text" name="keywords" value="{{empty($keywords)?'':$keywords}}" placeholder="输入关键字"/>--}}
            {{--                        <button type="submit"><i class="iconfont icon-search"></i></button>--}}
            {{--                    </form>--}}

            {{--                </div>--}}
            {{--            </div>--}}
            @if(\Module\Member\Auth\MemberUser::id())
                <div class="item">
                    <a href="{{modstart_web_url('member')}}" class="sub-title">
                        <img class="account" src="@asset('vendor/Site/image/has-login.png')" />

                    </a>
                    <div class="sub-nav">
                        <div class="username">{{\Module\Member\Auth\MemberUser::get('username')}}</div>
                        {!! \Module\Member\Config\MemberNavMenu::render() !!}
                        {{--                        <a class="sub-nav-item" href="javascript:;"--}}
                        {{--                           data-href="{{modstart_web_url('logout')}}" data-confirm="确认退出登录？">--}}
                        {{--                            退出登录--}}
                        {{--                        </a>--}}
                    </div>
                </div>
            @else
                <div class="item">
                    <a href="{{modstart_web_url('login',['redirect'=>\ModStart\Core\Input\Request::redirectUrl()])}}" rel="nofollow">
                        <div class="login-avar"><img class="account" src="@asset('vendor/Site/image/no-login.png')" /></div>
                        <p>登录</p>
                    </a>
                </div>
            @endif
            <ul>

                @foreach(\Module\Nav\Util\NavUtil::listByPositionWithCache('head') as $key=>$nav)
                    <li>
                        <a class="title" href="{{$nav['link']}}">
                            <img class="brand-img" src="{{\ModStart\Core\Assets\AssetsUtil::fix($nav['iconMo'])}}" alt="">
                            <p>{{$nav['name']}}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
            @if(\Module\Member\Auth\MemberUser::id())
                <div class="loginout">
                    <div class="sub-nav">
                        <a class="sub-nav-item" href="javascript:;"
                           data-href="{{modstart_web_url('logout')}}" data-confirm="确认退出登录？">
                            <img class="brand-img" src="@asset('vendor/Site/image/loginout.png')">
                            <p>退出登录</p>
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div class="m-menu">

            @if(\Module\Member\Auth\MemberUser::id())
                <div class="item">
                    <a href="{{modstart_web_url('member')}}" class="sub-title">
                        <img class="account" src="@asset('vendor/Site/image/acount-login.png')" />

                    </a>
                    <div class="sub-nav">
                        <div>{{\Module\Member\Auth\MemberUser::get('username')}}</div>
                        {!! \Module\Member\Config\MemberNavMenu::render() !!}
                        <a class="sub-nav-item" href="javascript:;"
                           data-href="{{modstart_web_url('logout')}}" data-confirm="确认退出登录？">
                            退出登录
                        </a>
                    </div>
                </div>
            @else
                <a href="{{modstart_web_url('login',['redirect'=>\ModStart\Core\Input\Request::redirectUrl()])}}" rel="nofollow">
                    <img class="account" src="@asset('vendor/Site/image/account-icon.png')" />
                </a>
            @endif
            <img class="path-icon iconfont icon-list" src="@asset('vendor/Site/image/path.png')"  onclick="MS.header.trigger()"/>
            {{--            <img src="@asset('vendor/Site/image/lang-cn.png')" />--}}
            {{--            <img src="@asset('vendor/Site/image/search-logo.png')" />--}}
            {{--            <img src="@asset('vendor/Site/image/cart.png')" />--}}
        </div>

    </div>
    <div class="ub-m-container  ub-mars-top2">
        {{--        {!! \Module\Nav\View\NavView::position('head') !!}--}}
        <ul>
            @foreach(\Module\Nav\Util\NavUtil::listByPositionWithCache('head') as $key=>$nav)
                <li><a href="{{$nav['link']}}">{{$nav['name']}}</a></li>
            @endforeach
        </ul>
    </div>
    <script>
        $(function () {
            $(".path-icon1").click(function (){
                $('.ub-mars-top2').slideToggle();
            });
        });
    </script>
</header>
