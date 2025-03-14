@extends($_viewFrame)

@section('pageTitleMain')手机快捷登录@endsection
@section('pageKeywords')手机快捷登录@endsection
@section('pageDescription')手机快捷登录@endsection

@section('headAppend')
    @parent
    <link rel="canonical" href="{{modstart_web_url('login/phone')}}"/>
    {!! \ModStart\Core\Hook\ModStartHook::fireInView('MemberLoginPageHeadAppend'); !!}
@endsection

@section('bodyAppend')
    @parent
    {{\ModStart\ModStart::js('asset/common/commonVerify.js')}}
    <script>
        $(function () {
            new window.api.commonVerify({
                generateServer: '{{$__msRoot}}login/phone_verify',
                selectorTarget: 'input[name=phone]',
                selectorGenerate: '[data-phone-verify-generate]',
                selectorCountdown: '[data-phone-verify-countdown]',
                selectorRegenerate: '[data-phone-verify-regenerate]',
                @if(!\Module\Member\Util\SecurityUtil::loginCaptchaProvider())
                    selectorCaptcha: 'input[name=captcha]',
                    selectorCaptchaImg:'img[data-captcha]',
                @endif
                interval: 60,
                formData:function(){
                    var $provider = $('[data-captcha-provider]');
                    var data = {};
                    $provider.find('input').each(function () {
                        var $this = $(this);
                        data[$this.attr('name')] = $this.val();
                    });
                    return data;
                }
            },window.api.dialog);
        });
    </script>
    {!! \ModStart\Core\Hook\ModStartHook::fireInView('MemberLoginPageBodyAppend'); !!}
@endsection


@section('bodyContent')
    {{--@if(\ModStart\Core\Util\AgentUtil::isMobile())
        <div class="ub-account member-login-m pb-member-login-account">

            <div class="box" data-member-login-box>
                <div class="nav">
                    <a href="javascript:;" class="active">登录</a>
                    @if(!modstart_config('registerDisable',false) && !modstart_config('Member_LoginPhoneAutoRegister', false))
                        ·
                        <a href="{{$__msRoot}}register?redirect={{!empty($redirect)?urlencode($redirect):''}}">注册</a>
                    @endif
                </div>

                <div class="ub-form flat">
                    <form action="{{\ModStart\Core\Input\Request::currentPageUrl()}}" method="post" data-ajax-form>
                        <div class="line">
                            <div class="field">
                                <input type="text" class="form-lg" name="phone" placeholder="输入手机" />
                            </div>
                        </div>
                        @if($provider=\Module\Member\Util\SecurityUtil::loginCaptchaProvider())
                            <div style="padding:0.5rem;" data-captcha-provider>
                                <div>
                                    {!! $provider->render() !!}
                                </div>
                            </div>
                        @else
                            <div class="line">
                                <div class="field">
                                    <div class="row no-gutters">
                                        <div class="col-6">
                                            <input type="text" class="form-lg" name="captcha" autocomplete="off" placeholder="图片验证码" />
                                        </div>
                                        <div class="col-6">
                                            <img class="captcha captcha-lg" data-captcha title="刷新验证" onclick="this.src='{{$__msRoot}}login/phone_captcha?'+Math.random()" src="{{$__msRoot}}login/phone_captcha?{{time()}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="line">
                            <div class="field">
                                <div class="row no-gutters">
                                    <div class="col-6">
                                        <input type="text" class="form-lg" name="verify" placeholder="输入验证码" />
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-lg btn-block" type="button" data-phone-verify-generate>获取验证码</button>
                                        <button class="btn btn-lg btn-block" type="button" data-phone-verify-countdown style="display:none;margin:0;"></button>
                                        <button class="btn btn-lg btn-block" type="button" data-phone-verify-regenerate style="display:none;margin:0;">重新获取</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="line">
                            <div class="field">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">登录</button>
                                <input type="hidden" name="redirect" value="{{empty($redirect)?'':$redirect}}">
                                @if(modstart_config('Member_LoginPhoneAutoRegister', false))
                                    <div class="ub-text-muted">
                                        未注册的手机号，我们将自动帮您注册账号
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                @include('module::Member.View.pc.oauthButtons')

                @if(!modstart_config('retrieveDisable',false))
                    <div class="retrieve">
                        忘记密码?
                        <a target="_parent" href="{{$__msRoot}}retrieve?redirect={{urlencode($redirect)}}">找回密码</a>
                    </div>
                @endif
            </div>

        </div>--}}
    @if(\ModStart\Core\Util\AgentUtil::isMobile())
        <div class="ub-m-container member-login-m pb-member-login-account  member-login-pc">
    @else
        <div class="ub-account pb-member-login-account member-login-pc">
    @endif
            <div class="box1 mars-box1 mars-login" data-member-login-box>
                @if(\ModStart\Core\Util\AgentUtil::isMobile())
                    <div class="nav-m   nav-event">
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
                    <form action="{{\ModStart\Core\Input\Request::currentPageUrl()}}" method="post" data-ajax-form>
                        <div class="line">
                            <div class="field">
                                <img class="input-img" src="@asset('vendor/Site/image/account.png')" alt="">
                                <input type="text" class="form-lg" name="phone" placeholder="输入手机" />
                            </div>
                        </div>
                        @if($provider=\Module\Member\Util\SecurityUtil::loginCaptchaProvider())
                            <div style="padding:0.5rem;" data-captcha-provider>
                                <div>
                                    {!! $provider->render() !!}
                                </div>
                            </div>
                        @else
                            <div class="line">
                                <div class="field">
                                    <div class="row no-gutters">
                                        <div class="col-6">
                                            <input type="text" class="form-lg" name="captcha" autocomplete="off" placeholder="图片验证码" />
                                        </div>
                                        <div class="col-6">
                                            <img class="captcha captcha-lg" data-captcha title="刷新验证" onclick="this.src='{{$__msRoot}}login/phone_captcha?'+Math.random()" src="{{$__msRoot}}login/phone_captcha?{{time()}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="line">
                            <div class="field">
                                <div class="row no-gutters">
                                    <div class="col-6">
                                        <input type="text" class="form-lg" name="verify" placeholder="输入验证码" />
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-lg btn-block" type="button" data-phone-verify-generate>获取验证码</button>
                                        <button class="btn btn-lg btn-block" type="button" data-phone-verify-countdown style="display:none;margin:0;"></button>
                                        <button class="btn btn-lg btn-block" type="button" data-phone-verify-regenerate style="display:none;margin:0;">重新获取</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!modstart_config('retrieveDisable',false))
                            <div class="retrieve">
                                忘记密码?
                                <a target="_parent" href="{{$__msRoot}}retrieve?redirect={{urlencode($redirect)}}">找回密码</a>
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
                    </form>
                </div>
                    @if(!\ModStart\Core\Util\AgentUtil::isMobile())
                        <div class="loginregister-close" ><a href="{{modstart_web_url('')}}"> <img src="@asset('vendor/Site/image/login-close.png')" alt=""> </a></div>
                    @endif
                @include('module::Member.View.pc.oauthButtons')


            </div>

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
@endsection
