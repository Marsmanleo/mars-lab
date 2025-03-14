@extends($_viewFrame)

@section('pageTitle'){{modstart_config('siteName').' - '.modstart_config('siteSlogan')}}@endsection

@section('bodyContent')

    <div class="view-pc">
    @foreach([MContentBlock::getCached('index-banner1')] as $cb)
            <?php if(empty($cb)) continue;?>
    <div class="banner-pic" style="background-image:url({{\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][1] ?? '')}});">
        <div class="banner-col banner-text">
            <div class="banner-logo"><img src="{{$cb['images'][0] ?? ''}}"/></div>
            <div class="banner-kh">創新，不止於此</div>
            <div class="banner-search">
                <div class="box">
                    <form action="{{modstart_web_url('search')}}" method="get">
                        <input type="text" name="keywords" value="{{empty($keywords)?'':$keywords}}" placeholder="输入关键字"/>
                        <button type="submit"><i class="iconfont icon-search"></i>搜索</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="banner-col banner-img"><img src="{{$cb['images'][2] ?? ''}}"/></div>
    </div>
    @endforeach
    <div class="ub-container">
        <div class="product-cate row">
            @foreach(\MCms::listContentByCatUrl('brands',1,6,[]) as $index=>$record)
                <div class="col-2">
                    <img src="{{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" alt="">
                    <p>{{$record['title']}}</p>
                </div>
            @endforeach

        </div>
    </div>

    <div class="ub-container index-brand-list">
{{--        @for ($i = 1; $i < 4; $i++)--}}
{{--            <div class="product-info1 color{{$i}}" style="background-image:url({{\ModStart\Core\Assets\AssetsUtil::fix(modstart_config('productAdBg' . $i))}});">--}}
{{--                <p class="product-kh">{{modstart_config('productKh' . $i)}}</p>--}}
{{--                <p class="product-desc">{{modstart_config('productDesc' . $i)}}</p>--}}
{{--                <button>進一步了解</button>--}}
{{--            </div>--}}
{{--        @endfor--}}
        @foreach(MContentBlock::allCached('index-brand',5) as $index=>$cb)
            <?php if(empty($cb)) continue;?>
            @if ($index == 2)
            <div class=" one-pic-banner index-brand li{{$index}}" style="background-image:url(@asset('vendor/Site/image/product-bg3.png'));background-size: cover;">
            @else
            <div class=" one-pic-banner index-brand li{{$index}}">
                @endif
                <div class="more-text">
                    @if(!empty($cb['images']))
                        @foreach($cb['images'] as $img)
                            <img src="{{$img}}" alt="">
                        @endforeach
                    @endif
                    <div>
                        @foreach($cb['texts'] as $index=>$text)
                            <p>{{$text}}</p>
                        @endforeach
                        <a href="{{modstart_web_url($cb['url'])}}"><button  class="banner-button">進一步了解</button></a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    <!-- my cases -->
    <div class="ub-container">
        <div class="mars-cases  cases-pc">
            <div class="head">Cases</div>
            <div class="desc">美容院室內設計案例分享</div>
            @foreach(\MCms::listContentByCatUrl('cases',1,6,['where'=>['isRecommend'=>true]]) as $index=>$record)
            <div class="cases-list {{'li'.$index}} {{$index==0 ? "other-css":"default-css"}}">
                <div class="case-info1">
                    <div class="mengban">
                        <img src="{{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" alt="">
                    </div>
                    <div class="case-float">
                        <div class="case-text">
                            <p class="case-title">{{$record['title']}}</p>
                            <p  class="case-desc">{{$record['summary']}}</p>
                            <a class="image" href="{{$record['_url']}}"><button>了解更多</button></a>
                        </div>
                    </div>
                </div>  
                <div class="case-info2">
                    <div>
                        <img src="@asset('vendor/Site/image/cases-logo.png')" alt="">
                    <p class="brand">{{strtolower($record['brand'])}}</p>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
        <div class="mars-cases-m">

            <div class="head">Cases</div>
            <div class="desc">美容院室內設計案例分享</div>
            @foreach(\MCms::listContentByCatUrl('cases',1,6,['where'=>['isRecommend'=>true]]) as $index=>$record)

                <div class="cases-list {{'li'.$index}}">
                    <div class="case-info1">
                        <div class="mengban">
                            <img src="{{$record['coverMo'] ? \ModStart\Core\Assets\AssetsUtil::fix($record['coverMo']) :\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" alt="">
                        </div>
                        <div class="case-float {{$index==0 ? "other-css":"default-css"}}">
                            <div class="case-text">
                                <p class="case-title">{{$record['title']}}</p>
                                <p  class="case-desc">{{$record['summary']}}</p>
                                <a class="image" href="{{$record['_url']}}"><button>了解更多</button></a>
                            </div>
                        </div>
                    </div>
                    <div class="case-info2">
                        <div>
                            <img src="@asset('vendor/Site/image/cases-logo.png')" alt="">
                        </div>
                        <p class="brand">{{strtolower($record['brand'])}}</p>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <!--more -->
    @foreach([MContentBlock::getCached('mars-more')] as $cb)
        <?php if(empty($cb)) continue;?>
        <div class="ub-container one-pic-banner mars-more" >

            <div class="mengban" style="background-image:url({{\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0] ?? \ModStart\Core\Assets\AssetsUtil::fix('vendor/Site/image/more-bg1.png'))}});background-size: cover;"></div>
{{--            @if(!empty($cb['imagesMo'])||!empty($cb['images']))--}}
{{--                @foreach($cb['imagesMo']??$cb['images'] as $img)--}}
{{--                    <img src="{{$img}}" alt="">--}}
{{--                @endforeach--}}
{{--            @endif--}}
            <div class="more-text">
                <div>
                    @foreach($cb['texts'] as $index=>$text)
                        <p>{{$text}}</p>
                    @endforeach

                    <button  class="banner-button">了解更多</button>
                </div>
            </div>
        </div>
    @endforeach
    <div class="ub-container mars-swiper" style="background:#FFF;">
        @if(\ModStart\Core\Util\AgentUtil::isMobile())
            {!! \Module\Banner\View\BannerView::basic('Cms',null,'5-3') !!}
        @else
            {!! \Module\Banner\View\BannerView::basic('Cms',null,'2-1') !!}
        @endif
    </div>
    @foreach([MContentBlock::getCached('index-footer-banner')] as $cb)
                        <?php if(empty($cb)) continue;?>
    <div class="ub-container one-pic-banner footer-banner" style="background-image:url({{\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0] ?? 'vendor/Site/image/footer-banner.png')}});background-size: cover;">
{{--        <img src="{{\ModStart\Core\Assets\AssetsUtil::fix(modstart_config('footerBanner'))}}" alt="">--}}
        <div class="more-text">
            <div>
                @if(!empty($cb['textsMo'])||!empty($cb['texts']))
                    @foreach($cb['texts']??$cb['textsMo'] as $index=>$text)
                        <p>{{$text}}</p>
                    @endforeach
                @endif
                <button>了解更多</button>
            </div>
        </div>
        <div class="f-icon left-icon">
            <img src="@asset('vendor/Site/image/left-icon.png')" alt="">
        </div>
        <div class="f-icon right-icon">
            <img src="@asset('vendor/Site/image/right-icon.png')" alt="">
        </div>
    </div>
    @endforeach
</div>
    <div class="view-m">
        @foreach([MContentBlock::getCached('index-banner1')] as $cb)
            <?php if(empty($cb)) continue;?>
            <div class="ub-m-container one-pic-banner first-banner">


                <img src="{{$cb['imagesMo'][0] ?? ''}}" alt="">
                <div class="more-text">
                    <div>
                        @if(!empty($cb['textsMo'])||!empty($cb['texts']))
                            @foreach($cb['textsMo']??$cb['texts'] as $index=>$text)
                                <p>{{$text}}</p>
                            @endforeach
                        @endif
                    </div>
                    <div class="banner-search">
                        <div class="box">
                            <form action="{{modstart_web_url('search')}}" method="get">
                                <input type="text" name="keywords" value="{{empty($keywords)?'':$keywords}}" placeholder="输入关键字"/>
                                <button type="submit"><i class="iconfont icon-search"></i>搜索</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="ub-m-container">
            <div class="product-cate">
                <ul>
                    <li>
                        <img src="@asset('vendor/Site/image/space.png')" alt="">
                        <p>Space</p>
                    </li>
                    <li>
                        <img src="@asset('vendor/Site/image/Creative.png')" alt="">
                        <p>Creative</p>
                    </li>
                    <li>
                        <img src="@asset('vendor/Site/image/selection.png')" alt="">
                        <p>Selection</p>
                    </li>
                    <li>
                        <img src="@asset('vendor/Site/image/tree.png')" alt="">
                        <p>Tree</p>
                    </li>
                    <li>
                        <img src="@asset('vendor/Site/image/education.png')" alt="">
                        <p>Education</p>
                    </li>
                    <li>
                        <img src="@asset('vendor/Site/image/business.png')" alt="">
                        <p>Business</p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="ub-m-container">

            @foreach(MContentBlock::allCached('index-brand',5) as $cb)
                <?php if(empty($cb)) continue;?>
                <div class="ub-m-container one-pic-banner index-brand">

                    @if(!empty($cb['imagesMo'])||!empty($cb['images']))
                        @foreach($cb['imagesMo']??$cb['images'] as $img)
                            <img src="{{$img}}" alt="">
                        @endforeach
                    @endif
                    <div class="more-text">
                        <div>
                            @foreach($cb['texts'] as $index=>$text)
                                <p>{{$text}}</p>
                            @endforeach
                            <button  class="banner-button">進一步了解</button>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
        <!-- my cases -->
        <div class="ub-m-container">
            <div class="mars-cases">

                <div class="head">Cases</div>
                <div class="desc">美容院室內設計案例分享</div>
                @foreach(\MCms::listContentByCatUrl('cases',1,6,['where'=>['isRecommend'=>true]]) as $index=>$record)

                    <div class="cases-list {{'li'.$index}}">
                        <div class="case-info1">
                            <div class="mengban">
                            <img src="{{$record['coverMo'] ? \ModStart\Core\Assets\AssetsUtil::fix($record['coverMo']) :\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" alt="">
                            </div>
                            <div class="case-float {{$index==0 ? "other-css":"default-css"}}">
                                <div class="case-text">
                                    <p class="case-title">{{$record['title']}}</p>
                                    <p  class="case-desc">{{$record['summary']}}</p>
                                    <a class="image" href="{{$record['_url']}}"><button>了解更多</button></a>
                                </div>
                            </div>
                        </div>
                        <div class="case-info2">
                            <div>
                                <img src="@asset('vendor/Site/image/cases-logo.png')" alt="">
                            </div>
                            <p class="brand">{{strtolower($record['brand'])}}</p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
            @if (!empty(MContentBlock::getCached('mars-more')))
        @foreach([MContentBlock::getCached('mars-more')] as $cb)
                    <?php if(empty($cb)) continue;?>
            <div class="ub-m-container one-pic-banner mars-more">

                @if(!empty($cb['imagesMo'])||!empty($cb['images']))
                    @foreach($cb['imagesMo']??$cb['images'] as $img)
                        <img src="{{$img}}" alt="">
                    @endforeach
                @endif
                <div class="more-text">
                    <div>
                        @foreach($cb['texts'] as $index=>$text)
                            <p>{{$text}}</p>
                        @endforeach

                        <button  class="banner-button">了解更多</button>
                    </div>
                </div>
            </div>
        @endforeach
            @endif
            <div class="ub-m-container cms-banner" style="background:#FFF;">
                {!! \Module\Banner\View\BannerView::basic('Cms-m',null,'2-1', '2-1') !!}
            </div>
        @foreach([MContentBlock::getCached('index-footer-banner')] as $cb)
                <?php if(empty($cb)) continue;?>
            <div class="ub-m-container one-pic-banner footer-banner">

                <img src="{{$cb['imagesMo'][0] ?? ''}}" alt="">
                <div class="more-text">
                    <div>
                        @if(!empty($cb['textsMo'])||!empty($cb['texts']))
                        @foreach($cb['textsMo']??$cb['texts'] as $index=>$text)
                            <p>{{$text}}</p>
                        @endforeach
                        @endif
                        <button>了解更多</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
     
@endsection
