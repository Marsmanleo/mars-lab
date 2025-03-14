@extends($_viewFrame)

@section('pageTitle'){{modstart_config('siteName').' - '.modstart_config('siteSlogan')}}@endsection

@section('bodyContent')

    @foreach([MContentBlock::getCached('index-banner1')] as $cb)

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
                            <button type="submit"><img class="search" src="@asset('vendor/Site/image/search-logo.png')" />搜索</button>
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
            @foreach(\MCms::listContentByCatUrl('cases',1,4,['where'=>['isRecommend'=>true]]) as $index=>$record)

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

    @foreach([MContentBlock::getCached('mars-more')] as $cb)

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
    <div class="ub-m-container cms-banner" style="background:#FFF;">
        {!! \Module\Banner\View\BannerView::basic('Cms',null,'2-1', '2-1') !!}
    </div>
    @foreach([MContentBlock::getCached('index-footer-banner')] as $cb)

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

    
     
@endsection
