@extends($_viewFrame)

@section('pageTitleMain'){{$cat['seoTitle']?$cat['seoTitle']:$cat['title']}}@endsection
@section('pageKeywords'){{$cat['seoKeywords']?$cat['seoKeywords']:$cat['title']}}@endsection
@section('pageDescription'){{$cat['seoDescription']?$cat['seoDescription']:$cat['title']}}@endsection

@section('bodyAppend')
    @parent
    {{\ModStart\ModStart::js('asset/common/commonVerify.js')}}
    {{\ModStart\ModStart::js('vendor/Member/entry/register.js')}}
    <script>
        $(function () {
            new window.api.commonVerify({
                generateServer: '{{$__msRoot}}retrieve/email_verify',
                selectorTarget: 'input[name=email]',
                selectorGenerate: '[data-email-verify-generate]',
                selectorCountdown: '[data-email-verify-countdown]',
                selectorRegenerate: '[data-email-verify-regenerate]',
                interval: 60
            },window.api.dialog);
            new window.api.commonVerify({
                generateServer: '{{$__msRoot}}retrieve/phone_verify',
                selectorTarget: 'input[name=phone]',
                selectorGenerate: '[data-phone-verify-generate]',
                selectorCountdown: '[data-phone-verify-countdown]',
                selectorRegenerate: '[data-phone-verify-regenerate]',
                interval: 60
            },window.api.dialog);
        });
    </script>
@endsection
@section('bodyContent')
<div class="view-pc">
    <div class="ub-container mars-list-top">

        @if($cat['bannerBg'])
            <div class="mars-news-banner" style="background-image:url({{\ModStart\Core\Assets\AssetsUtil::fix($cat['bannerBg'])}});background-size: cover;"> </div>
        @else
            <div class="mars-news-banner" style="background-image:url({{\ModStart\Core\Assets\AssetsUtil::fix('vendor/Site/image/news-banner.png')}});background-size: cover;"> </div>
        @endif
        <div class="more-text">
            <div>
                <p class="breadcrumb"><a href="{{modstart_web_url('')}}">首页</a><img src="@asset('vendor/Site/image/right-alt.png')" alt=""><span>最新消息</span></p>
                @foreach([MContentBlock::getCached('new-lastest-msg')] as $cb)
                    @foreach($cb['texts'] as $index=>$text)
                        <p>{{$text}}</p>
                    @endforeach
                @endforeach
            </div>
        </div>


    </div>

    <div class="ub-container   mars-list-content the-lastest">
        <div class="mars-news-head">
            最新文章
        </div>
        <div class="mars-lastest-news">
            @foreach(\MCms::listContentByCatUrl('news',1,5,['where'=>['isTop'=>true]]) as $key=>$record)

                @if($key < 1)
                    <div class="the-news first-new">
                            <div class="first-new-title">

                                <img src="{{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" />
                                <div class="new-title"><a href="{{$record['_url']}}">{{$record['title']}}</a></div>
                            </div>
                        <div class="first-new-logo">
                            <img src="@asset('vendor/Site/image/cases-logo.png')" alt="">
                        </div>
                    </div>
                @else
                    <div class="the-news other-new">
                            <img class="fl-left" src="{{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" />
                            <div class="new-title fl-left">
                                <p>
                                    @if(!empty($record['_tags']))

                                        @foreach($record['_tags'] as $t)
                                            <a href="{{modstart_web_url('tag/'.urlencode($t))}}"
                                               class="news-tag">#{{$t}}</a>
                                        @endforeach
                                    @else
                                         <a href="{{$catRoot['_url']}}"
                                                                                   class="news-tag">#Mars lab</a>
                                    @endif
                                </p>
                                <p><a href="{{$record['_url']}}">{{$record['title']}}</a></p>
                                <p>{{($record['postTime'])}}</p>
                                <p>{{$record['viewCount']?$record['viewCount']:'999+'}} read</p>
                            </div>
                    </div>
                @endif

            @endforeach
        </div>
    </div>
    <div class="ub-container  mars-list-content">
        <div class="mars-news-head">
            更多文章
        </div>
        <div class="mars-news-cate">
{{--            <div class="cat-list"><a class="title @if($catRoot['url']==\ModStart\Core\Input\Request::path()) active @endif"--}}
{{--                                     href="{{$catRoot['_url']}}">全部</a></div>--}}
            @foreach($catRootChildren as $c)
                <div class="cat-list"><a class="title @if(\ModStart\Core\Input\Request::path()==$c['url']) active @endif"
                        href="{{$c['_url']}}">{{$c['title']}}</a></div>
            @endforeach

        </div>
    </div>

    <div class="ub-container mars-list-content mars-news-items mars-lastest-news">
        <div class="body" style="padding:0;">
            @if(empty($records))
                <div class="ub-empty tw-my-20">
                    <div class="icon">
                        <div class="iconfont icon-empty-box"></div>
                    </div>
                    <div class="text">暂无记录</div>
                </div>
            @else
                @foreach($records as $key=>$record)
                    @if($key < 2)
                        <div class="the-news first-new">
                                <div class="first-new-title">
                                    <img src="{{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" />
                                    <div class="new-title"><a href="{{$record['_url']}}">{{$record['title']}}</a></div>
                                </div>
                            <div class="first-new-logo">
                                <img src="@asset('vendor/Site/image/cases-logo.png')" alt="">
                            </div>
                        </div>
                    @else
                        <div class="the-news other-new {{($key+1) % 3 !==0 ? 'mg-left' : ''}}">
                                <img  src="{{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" />
                                <div class="new-title">
                                    <p>
                                        @if(!empty($record['_tags']))

                                            @foreach($record['_tags'] as $t)
                                                <a href="{{modstart_web_url('tag/'.urlencode($t))}}"
                                                   class="news-tag">#{{$t}}</a>
                                            @endforeach
                                        @else
                                            <a href="{{$catRoot['_url']}}"
                                               class="news-tag">#Mars lab</a>
                                        @endif
                                    </p>
                                    <p><a href="{{$record['_url']}}">{{$record['title']}}</a></p>
                                    <p>{{($record['postTime'])}}</p>
                                    <p>{{$record['viewCount']?$record['viewCount']:'999+'}} read</p>
                                </div>
                        </div>
                    @endif
                @endforeach
                <div class="ub-page">
                    {!! $pageHtml !!}
                </div>
            @endif
        </div>

    </div>

    <div class="ub-container mars-subscribe">
        <div class="mars-subscribe-content">

        </div>
    </div>
    @foreach([MContentBlock::getCached('news-footer')] as $cb)

        <div class="ub-container footer-subscribe" style="background-image:url({{isset($cb['images'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0]):\ModStart\Core\Assets\AssetsUtil::fix('vendor/Site/image/more-bg.png')}});background-size: cover;background-position: right;">

            <div class="more-text1">
                <div class="content">
                    <p>{{$cb['texts'][0] ?? '訂閱電子報'}}</p>
                    <p>{{$cb['texts'][1] ?? '最新，最實用的商業秘籍在這，重要訊息不漏接'}}</p>
                        <form action="{{ modstart_api_url('cms/form/submit',['cat'=>13]) }}" method="post" data-ajax-form>

                            <div class="row">
                                <div class="col-md-9 ">
                                    <input type="email" name="email" placeholder="輸入您的電子郵件"/>
                                    <p class="tips">{{$cb['texts'][2] ?? '無垃圾郵件，隨時取消'}}</p>
                                </div>
                                <div class="col-md-3 "><button  type="submit">{{$cb['texts'][3] ?? '立即订阅1'}}</button></div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="view-m news-body">
    <div class="ub-m-container mars-list-top list">

        <div class="mars-news-banner news-banner-bg"></div>
        <div class="more-text">
            <div>
                <p class="breadcrumb">
                    <img src="@asset('vendor/Site/image/lastest-news.png')" alt="">
                </p>
                @foreach([MContentBlock::getCached('new-lastest-msg')] as $cb)
                    @foreach($cb['texts'] as $index=>$text)
                        <p>{{$text}}</p>
                    @endforeach
                @endforeach
            </div>
        </div>


    </div>

    <div class="ub-m-container   mars-list-lastest ">
    <div class="ub-m-container   mars-list-content ">
        <div class="mars-news-head t-right">
            最新文章
        </div>
        <div class="mars-news-cate"></div>

    </div>
    <div class="ub-m-container brand-part lastest-news">
        <div class="example-css-tab">
            <div class="container dwo">
                <div class="card">
                    @foreach(\MCms::listContentByCatUrl('news',1,3,['where'=>['isTop'=>true]]) as $key=>$record)
                        <input type="radio" name="select" id="slide_{{$key+1}}" {{$key==0 ?'checked':''}} >
                    @endforeach
                    <input type="checkbox" id="slideImg">
                    <div class="slider">
                        @foreach(\MCms::listContentByCatUrl('news',1,3,['where'=>['isTop'=>true]]) as $key=>$record)
                            <label for="slide_{{$key+1}}" class="slide slide_{{$key+1}}"></label>
                        @endforeach
                    </div>
                    @foreach(\MCms::listContentByCatUrl('news',1,3,['where'=>['isTop'=>true]]) as $key=>$record)
                        <div class="inner_part">
                            <div class="content content_{{$key+1}}">

                                <div class="content-inner ">
                                    <div class="title">
                                        <img class="fl-left" src="{{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" />
                                        <div class="text">
                                            {{$record['title']}}
                                        </div>
                                    </div>
                                    <div class="cont">
                                        内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容......
                                        {{--                                        {!! \ModStart\Core\Util\HtmlUtil::replaceImageSrcToLazyLoad($record['_data']['content'],'data-src',true) !!}--}}
                                    </div>
                                    <div class="cont2  ">

                                        <p>{{($record['postTime'])}}&nbsp;&nbsp;&nbsp; </p>
                                        <p>{{$record['viewCount']?$record['viewCount']:'99+'}} read</p>

                                    </div>
                                    <div class="cont2 tag">
                                        <p>
                                            @if(!empty($record['_tags']))

                                                @foreach($record['_tags'] as $t)
                                                    <a href="{{modstart_web_url('tag/'.urlencode($t))}}"
                                                       class="news-tag">#{{$t}}</a>
                                                @endforeach
                                            @else
                                                <a href="{{$catRoot['_url']}}"
                                                   class="news-tag">#Mars lab</a>
                                            @endif
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @endforeach


                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="ub-m-container  mars-list-content today-news">
        <div class="head">今日快讯</div>
        <div class="content">
            <div class="li">
                <div class="num">
                    <img src="@asset('vendor/Site/image/news-li-1.png')" alt="">
                </div>
                <div class="title">
                    文章标题文章标题文章标题文章标题
                </div>
            </div>
            <div class="li">
                <div class="num">
                    <img src="@asset('vendor/Site/image/news-li-2.png')" alt="">
                </div>
                <div class="title">
                    文章标题文章标题文章标题文章标题
                </div>
            </div>
            <div class="li">
                <div class="num">
                    <img src="@asset('vendor/Site/image/news-li-3.png')" alt="">
                </div>
                <div class="title">
                    文章标题文章标题文章标题文章标题
                </div>
            </div>
            <div class="li">
                <div class="num">
                    <img src="@asset('vendor/Site/image/news-li-4.png')" alt="">
                </div>
                <div class="title">
                    文章标题文章标题文章标题文章标题
                </div>
            </div>
        </div>
    </div>
    <div class="ub-m-container  mars-list-content">
        <div class="mars-news-head">
            更多文章
        </div>
        <div class="mars-news-cate">
            {{--            <div class="cat-list"><a class="title @if($catRoot['url']==\ModStart\Core\Input\Request::path()) active @endif"--}}
            {{--                                     href="{{$catRoot['_url']}}">全部</a></div>--}}
            {{--            @foreach($catRootChildren as $c)--}}
            {{--                <div class="cat-list"><a class="title @if(\ModStart\Core\Input\Request::path()==$c['url']) active @endif"--}}
            {{--                                         href="{{$c['_url']}}">{{$c['title']}}</a></div>--}}
            {{--            @endforeach--}}

        </div>
    </div>

    <div class="ub-m-container mars-list-content mars-news-items mars-lastest-news">
        <div class="body" style="padding:0;">
            @if(empty($records))
                <div class="ub-empty tw-my-20">
                    <div class="icon">
                        <div class="iconfont icon-empty-box"></div>
                    </div>
                    <div class="text">暂无记录</div>
                </div>
            @else
                @foreach($records as $key=>$record)
                    <div class="the-news other-new">
                        <div class="new-title fl-left">

                            <p><a href="{{$record['_url']}}">{{$record['title']}}</a></p>
                            <p>{{($record['postTime'])}} </p>
                            <p> {{$record['viewCount']?$record['viewCount']:'99+'}} read </p>

                        </div>
                        <img class="fl-left" src="{{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" />
                    </div>

                @endforeach
                <div class="ub-page">
                    {!! $pageHtml !!}
                </div>
            @endif
        </div>

    </div>

    <div class="ub-m-container mars-subscribe">
        <div class="mars-subscribe-content">
            <div>{{$cb['textsMo'][0] ?? '訂閱電子報'}}</div>
            <div>{{$cb['textsMo'][1] ?? '最新，最實用的商業秘籍在這，重要訊息不漏接'}}</div>
            <form action="{{ modstart_api_url('cms/form/submit',['cat'=>13]) }}" method="post" data-ajax-form>
                <div><input type="email" name="email" placeholder="輸入您的電子郵件"/></div>
                <p class="tips">{{$cb['textsMo'][2] ?? '無垃圾郵件，隨時取消'}}</p>
                <div><button   type="submit">{{$cb['textsMo'][3] ?? '立即訂閱'}}</button></div>
            </form>
        </div>
    </div>

</div>
<script>
    $(document).ready(function() {

        $('.view-m .inner_part').each(function(index, ul){
            // 4、手指滑动轮播图
            var startX = 0;
            var moveX = 0;

            var index = 0;
            var flag = false;
            // 1、触摸元素touchstart：获取手指初始坐标
            ul.addEventListener('touchstart', function (e) {

                startX = e.targetTouches[0].pageX;
            });
            // 2、移动手指 touchmove ：计算手指的滑动距离 ， 并且移动盒子
            ul.addEventListener('touchmove', function (e) {

                moveX = e.targetTouches[0].pageX;
                flag = true;   // 如果用户手指移动过我们再去判断否则不做判断效果
                e.preventDefault(); // 阻止滚动屏幕的行为
            });
            // 手指离开 根据移动离开去判断是回弹还是播放上一张或者下一张
            ul.addEventListener('touchend', function (e) {
                if (flag) {
                    if (Math.abs(moveX-startX) > 20) {
                        var radios = document.getElementsByName('select');

                        // 如果是右滑 就是 播放上一张  moveX 是正值
                        if ((moveX-startX) > 0) {
                            for (var i = 0; i < radios.length; i++) {
                                if (radios[i].checked) {
                                    if (i < 1) {
                                        radios[radios.length-1].checked = true;
                                    } else {
                                        radios[i - 1].checked = true;
                                    }
                                    break;
                                }
                            }
                        } else {
                            // 如果是左滑就是 播放下一张  moveX  是负值
                            index++;
                            for (var i = 0; i < radios.length; i++) {
                                if (radios[i].checked) {
                                    if (i < radios.length - 1) {
                                        radios[i + 1].checked = true;
                                    } else {
                                        radios[0].checked = true;
                                    }
                                    break;
                                }
                            }
                        }
                    } else {

                    }

                }

            })
        });
        window.addEventListener('load', function () {

        })


    });
</script>
@endsection





