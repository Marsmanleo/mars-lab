@extends($_viewFrame)

@section('pageTitleMain'){{$record['seoTitle']?$record['seoTitle']:$record['title']}}@endsection
@section('pageKeywords'){{$record['seoKeywords']?$record['seoKeywords']:$record['title']}}@endsection
@section('pageDescription'){{$record['seoDescription']?$record['seoDescription']:$record['title']}}@endsection

{!! \ModStart\ModStart::js('asset/common/lazyLoad.js') !!}
{!! \ModStart\ModStart::js('asset/common/htmlEnhance.js') !!}

@section('bodyContent')
    <div class="ub-m-container mars-list-top detail">

        @if($cat['bannerBgMo'])
            <div class="mars-news-banner  "><img src="{{\ModStart\Core\Assets\AssetsUtil::fix($cat['bannerBgMo'])}}" /></div>
        @else
            <div class="mars-news-banner " ><img src="@asset('vendor/Site/image/m-news-detail-banner.png')" alt=""></div>
        @endif
        <div class="more-text">
            <div class="breadcrumb">
                <p></p>
{{--                <p>--}}
{{--                <a href="{{modstart_web_url('')}}">首页</a><img src="@asset('vendor/Site/image/right-alt.png')" alt="">--}}
{{--                @foreach($catChain as $i=>$c)--}}
{{--                    <a href="{{modstart_web_url($c['url'])}}">{{$c['title']}}</a>--}}
{{--                    @if($i+1 != count($catChain))--}}
{{--                        <img src="@asset('vendor/Site/image/right-alt.png')" alt="">--}}
{{--                    @endif--}}
{{--                @endforeach--}}
{{--                </p>--}}
                <p>{{$record['title']}}</p>
            </div>
        </div>


    </div>




    <div class="ub-m-container news-detail-content">

        @if(!\MCms::canVisitCat($cat))
            <div class="ub-alert danger">
                <i class="iconfont icon-warning"></i>
                您没有权限访问该栏目内容
            </div>
        @else
            <div class="ub-content-box margin-bottom">
                <div class="ub-html lg">
                    {!! \ModStart\Core\Util\HtmlUtil::replaceImageSrcToLazyLoad($record['_data']['content'],'data-src',true) !!}
                </div>
                <div class="more-plan">
                    <div class="head">更多經營策略:</div>
                    <div class="content">4個數位轉型案例解析|掌握轉型步驟、品牌再造重點，成功重啟商機
                        【EDM行銷攻略】設計元素、製作要點全彙整，带你培養忠實客戶群</div>
                </div>
                <div class="tw-bg-gray-100 tw-my-4" style="height:1px;"></div>
                <div class="tw-flex">
                    <div class="tw-flex-grow">
                        @if(modstart_config('Cms_LikeAnonymityEnable',false))
                            {!! \Module\Cms\View\CmsView::likeBtn($record['id'],['count'=>$record['likeCount']]) !!}
                        @endif
                        &nbsp;
                    </div>
                    <div>
                        <i class="iconfont icon-eye"></i>
                        {{$record['viewCount']?$record['viewCount']:'-'}}
                        &nbsp;&nbsp;
                        <i class="iconfont icon-time"></i>
                        {{($record['postTime'])}}
                        &nbsp;&nbsp;
                        @foreach($record['_tags'] as $tag)
                            <i class="iconfont icon-tag"></i>
                            <a class="tw-bg-gray-100 tw-leading-6 tw-inline-block tw-px-3 tw-rounded-2xl tw-text-gray-800 tw-mr-2 tw-mb-2"
                               href="{{modstart_web_url('tag/'.urlencode($tag))}}">
                                {{$tag}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

{{--        <div class="margin-bottom ub-content-bg tw-rounded-lg tw-p-3">--}}
{{--            <div class="row">--}}
{{--                <div class="col-6">--}}
{{--                    <div>--}}
{{--                        <div class="tw-text-gray-400 tw-text-sm">上一篇</div>--}}
{{--                        <div class="tw-pt-2">--}}
{{--                            @if($recordPrev)--}}
{{--                                <a href="{{$recordPrev['_url']}}" class="tw-text-gray-800 tw-inline-block">--}}
{{--                                    {{$recordPrev['title']}}--}}
{{--                                </a>--}}
{{--                            @else--}}
{{--                                <span class="ub-text-muted">没有了</span>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-6">--}}
{{--                    <div class="tw-text-right">--}}
{{--                        <div class="tw-text-gray-400 tw-text-sm">下一篇</div>--}}
{{--                        <div class="tw-pt-2">--}}
{{--                            @if($recordNext)--}}
{{--                                <a href="{{$recordNext['_url']}}" class="tw-text-gray-800 tw-inline-block">--}}
{{--                                    {{$recordNext['title']}}--}}
{{--                                </a>--}}
{{--                            @else--}}
{{--                                <span class="ub-text-muted">没有了</span>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

    </div>

@endsection





