@extends($_viewFrame)

@section('pageTitle'){{(!empty($pageTitle)?$pageTitle.' - ':'').modstart_config('siteName')}}@endsection

@section('bodyContent')

    <div class="ub-container margin-top mars-member">
        <div class="row menu">
            <div class="col-md-3">


                <p>欢迎您！</p>
                <a href="{{modstart_web_url('member_profile/avatar')}}"
                   class=" " >{{\Module\Member\Auth\MemberUser::nickname()}}</a>
                <div class="tw-text-gray-400 tw-pt-1">
                    {{empty($_memberUser['signature'])?'暂无签名':$_memberUser['signature']}}
                </div>

            </div>
            <div class="col-md-3">
                @foreach(\Module\Member\Config\MemberMenu::get() as $menu)
{{--                    <div class="title">{!! $menu['icon'] !!} {{$menu['title']}}</div>--}}
                    <div class="items">
                        <a class="{{modstart_baseurl_active('/member')}}" href="/member">我的账户</a>
                        @foreach($menu['children'] as $item)
                            <a class="{{modstart_baseurl_active($item['url'])}}" href="{{$item['url']}}">{{$item['title']}}</a>
                        @endforeach
                    </div>
                @endforeach

            </div>

            <div class="col-md-3">
                <div class="items logout">
                    <a class="sub-nav-item" href="javascript:;"
                       data-href="{{modstart_web_url('logout')}}" data-confirm="确认退出登录？">退出</a>
                </div>
            </div>
            <div class="col-md-3">敬请期待......</div>
        </div>
        <div class="row bg view-pc"  style="background: url(@asset('vendor/Site/image/member-bg.png'))"></div>
        <div class="row bg view-m"  style="background: url(@asset('vendor/Site/image/member-bg-m.png'))"></div>
        <div class="row content">
            <div class="col-md-2 view-pc"></div>
            <div class="col-md-1 view-m"></div>
            <div class="col-md-10">
                @section('memberBodyContent')
                    {!! empty($content)?'':$content !!}
                @show
            </div>
        </div>
    </div>

@endsection
