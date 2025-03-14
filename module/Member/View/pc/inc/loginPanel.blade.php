@if(\ModStart\Core\Util\AgentUtil::isMobile())
    <div class="ub-m-container member-login-m pb-member-login-account   member-login-pc">
@else
    <div class="ub-account pb-member-login-account login-pc member-login-pc">
    @endif
        @if(modstart_config('Member_LoginDefault','default')=='other')
            <div style="min-height:15rem;" data-member-login-other></div>
        @else


        <div class="box1 mars-box1 mars-login" data-member-login-box>
            @if(\ModStart\Core\Util\AgentUtil::isMobile())
                <div class="nav-m  nav-event">
                    <img src="@asset('vendor/Site/image/welcome-back.png')" alt="">
                    <img class="toggle-form open" src="@asset('vendor/Site/image/up-open.png')" alt="">
                    <img class="toggle-form close"  src="@asset('vendor/Site/image/down-close.png')" alt="">
                    <a href="{{modstart_web_url('')}}"><img class="back" src="@asset('vendor/Site/image/back.svg')" alt=""></a>

                </div>
            @else
                <div class="view-pc nav nav-event">
                    <img class="welcome" src="@asset('vendor/Site/image/pc-welcome.png')" alt="">
                    <img class="hi" src="@asset('vendor/Site/image/pc-hi.png')" alt="">
                    <img class="toggle-form open" src="@asset('vendor/Site/image/up-open.png')" alt="">
                    <img class="toggle-form close"  src="@asset('vendor/Site/image/down-close.png')" alt="">

                </div>
                <div class="view-m nav-m  nav-event">
                    <img src="@asset('vendor/Site/image/welcome-back.png')" alt="">
                    <img class="toggle-form open" src="@asset('vendor/Site/image/up-open.png')" alt="">
                    <img class="toggle-form close"  src="@asset('vendor/Site/image/down-close.png')" alt="">

                </div>
            @endif
            <div class="ub-form ub-form-login flat">
                <form action="{{modstart_web_url('login')}}" method="post" data-ajax-form>
                    <div class="line">
                        <div class="field">
                            <img class="input-img" src="@asset('vendor/Site/image/account.png')" alt="">
                            <input type="text" class="form-lg" name="username" placeholder="输入用户" />
                        </div>
                    </div>
                    <div class="line">
                        <div class="field">
                            <img class="input-img" src="@asset('vendor/Site/image/password.png')" alt="">
                            <input type="password" class="form-lg" name="password" placeholder="输入密码" />
                            <img class="input-img eye" src="@asset('vendor/Site/image/eye.png')" alt="">
                        </div>
                    </div>
                    @if(modstart_config('loginCaptchaEnable',false))
                        @if($provider = \Module\Member\Util\SecurityUtil::loginCaptchaProvider())
                            <div style="padding:0.5rem;">
                                {!! $provider->render() !!}
                            </div>
                        @else
                            <div class="line">
                                <div class="field">
                                    <div class="row no-gutters">
                                        <div class="col-6">
                                            <input type="text" class="form-lg" name="captcha" autocomplete="off" placeholder="图片验证码" />
                                        </div>
                                        <div class="col-6">
                                            <img class="captcha captcha-lg" title="刷新验证" data-captcha
                                                 src="{{modstart_web_url('login/captcha')}}"
                                                 onclick="$(this).attr('src','{{modstart_web_url('login/captcha')}}?'+Math.random())" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @if(!modstart_config('retrieveDisable',false))
                        <div class="retrieve">
                            <a target="_parent" href="{{$__msRoot}}retrieve?redirect={{urlencode($redirect)}}">忘記密碼？</a>
                        </div>
                    @endif
                    <div class=" ">
                        <div class="field">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">登錄</button>
                            <input type="hidden" name="redirect" value="{{empty($redirect)?'':$redirect}}">
                            @if(modstart_config('Member_LoginPhoneAutoRegister', false))
                                <div class="ub-text-muted">
                                    未注册的手机号，我们将自动帮您注册账号
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="  login-footer ">
                        <div class="other-login">
                            其他方式登錄：<img src="@asset('vendor/Site/image/wei.png')" alt="">
                        </div>

                    </div>
                </form>
                <div class="  login-footer ">

                    <div class="login-logo">
                        <img src="@asset('vendor/Site/image/cases-logo.png')" alt="">
                    </div>
                    <div class="tab-register">
                        {{--                    <a href="javascript:;" class="active">登錄</a>--}}
                        @if(!modstart_config('registerDisable',false) && !modstart_config('Member_LoginPhoneAutoRegister', false))
                            ·
                            <a href="{{$__msRoot}}register?redirect={{!empty($redirect)?urlencode($redirect):''}}">沒有賬戶？立即註冊</a>
                        @endif
                    </div>
                </div>
            </div>
            @if(!\ModStart\Core\Util\AgentUtil::isMobile())
                    <div class="loginregister-close" ><a href="{{modstart_web_url('')}}"> <img src="@asset('vendor/Site/image/login-close.png')" alt=""> </a></div>
            @endif
            @include('module::Member.View.pc.oauthButtons')


        </div>
        @endif
    </div>
            <style>
                .ub-footer-link.reverse,.ub-header-mars,.ub-m-footer-link {
                    display:none;
                }
                @media screen and (min-width: 861px) {
                    html{
                        font-size: 16.6px!important;
                    }
                }
            </style>