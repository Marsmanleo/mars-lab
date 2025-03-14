@extends($_viewMemberFrame)

@section('pageTitleMain')密码设定@endsection
@section('pageKeywords')密码设定@endsection
@section('pageDescription')密码设定@endsection

@section('memberBodyContent')

    @include('module::Member.View.pc.memberProfile.securityNav')

    <div class="tw-px-3 tw-py-20 tw-rounded-b-lg tw-bg-white">
        <form action="{{\ModStart\Core\Input\Request::currentPageUrl()}}" class="ub-form" method="post" style=" " data-ajax-form>
            @if(empty($_memberUser['password']))
                <div class="line">
                    <div class="ub-alert warning"><i class="iconfont icon-warning"></i> 您还没有设定密码，请设定新密码</div>
                </div>
            @else
                <div class="line">
                    <div class="label">旧密码：</div>
                    <div class="field password">
                        <input type="password" class="form" name="passwordOld" placeholder="旧密码" />
                        <img class="eye"  src="@asset('vendor/Site/image/close-eye.svg')"/>
                    </div>
                </div>
            @endif
            <div class="line">
                <div class="label">新密码：</div>
                <div class="field password">
                    <input type="password" class="form" name="passwordNew"  placeholder="新密码"/>
                    <img class="eye"  src="@asset('vendor/Site/image/close-eye.svg')"/>
                </div>
            </div>
            <div class="line">
                <div class="label">重复新密码：</div>
                <div class="field password">
                    <input type="password" class="form" name="passwordRepeat"  placeholder="重复新密码"/>
                    <img class="eye"  src="@asset('vendor/Site/image/close-eye.svg')"/>
                </div>
            </div>
            <div class="line">
                <div class="label">&nbsp;</div>
                <div class="field">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </form>
    </div>
@endsection
