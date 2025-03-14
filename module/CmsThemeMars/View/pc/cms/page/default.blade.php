@extends($_viewFrame)

@section('pageTitleMain'){{$cat['seoTitle']?$cat['seoTitle']:$cat['title']}}@endsection
@section('pageKeywords'){{$cat['seoKeywords']?$cat['seoKeywords']:$cat['title']}}@endsection
@section('pageDescription'){{$cat['seoDescription']?$cat['seoDescription']:$cat['title']}}@endsection

{!! \ModStart\ModStart::js('asset/common/lazyLoad.js') !!}
@section('bodyContent')
    <div class="ub-container mars-list-top">

        @if($cat['bannerBg'])
            <div class="mars-news-banner"><img src="{{\ModStart\Core\Assets\AssetsUtil::fix($cat['bannerBg'])}}" /></div>
        @else
            <div class="mars-news-banner" ><img src="@asset('vendor/Site/image/news-detail-banner.png')" alt=""></div>
        @endif
        <div class="more-text">
            <div class="breadcrumb">
                <p>
                    <a href="{{modstart_web_url('')}}">首页</a><img src="@asset('vendor/Site/image/right-alt.png')" alt="">
                    @foreach($catChain as $i=>$c)
                        <a href="{{modstart_web_url($c['url'])}}">{{$c['title']}}</a>
                        @if($i+1 != count($catChain))
                            <img src="@asset('vendor/Site/image/right-alt.png')" alt="">
                        @endif
                    @endforeach
                </p>
                <p>{{$record['title']}}</p>
            </div>
        </div>


    </div>


    <div class="ub-container">
        <div class="row">
            <div class="col-md-12">
                <div class="ub-panel" style="padding:1rem;">
                    <div class="ub-article">

                        <div class="content ub-html lg">
                            {!! \ModStart\Core\Util\HtmlUtil::replaceImageSrcToLazyLoad($record['_data']['content'],'data-src',true) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection





