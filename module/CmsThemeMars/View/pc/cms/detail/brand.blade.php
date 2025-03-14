@extends($_viewFrame)

@section('pageTitleMain'){{$record['seoTitle']?$record['seoTitle']:$record['title']}}@endsection
@section('pageKeywords'){{$record['seoKeywords']?$record['seoKeywords']:$record['title']}}@endsection
@section('pageDescription'){{$record['seoDescription']?$record['seoDescription']:$record['title']}}@endsection

{!! \ModStart\ModStart::js('asset/common/lazyLoad.js') !!}
{!! \ModStart\ModStart::js('asset/common/wuxing.js') !!}
{!! \ModStart\ModStart::js('asset/common/calendar.js') !!}
@section('bodyContent')
    <script type="text/javascript">
        var chinaTime = ['子','丑','丑','寅','寅','卯','卯','辰','辰','巳','巳','午','午','未','未','申','申','酉','酉','戌','戌','亥','亥','子'],
            chinaMonth = ['正月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','腊月'],
            chinaDate = ['初一','初二','初三','初四','初五','初六','初七','初八','初九','初十','十一','十二','十三','十四','十五','十六','十七','十八','十九','二十','廿一','廿二','廿三','廿四','廿五','廿六','廿七','廿八','廿九','三十'];
        $(function() {
            initDateSelect();

            $(".type").on("change", function(){
                if(this.value == '0') {
                    $(this).closest('.p2').find(".nongli").hide();
                    $(this).closest('.p2').find(".gongli").css("display", "inline-block");
                }else{
                    $(this).closest('.p2').find(".nongli").css("display", "inline-block");
                    $(this).closest('.p2').find(".gongli").hide();
                }
            });

            $(".go").on("click", function(){
                var year = month = date = -1;
                var type = $(this).closest('.p2').find(".type").val();
                var lunar;
                year = Number($(this).closest('.p2').find(".year").val());
                //公历
                if(type == '0') {
                    month = Number($(this).closest('.p2').find(".ylMonth").val());
                    date = Number($(this).closest('.p2').find(".ylDate").val());
                    lunar = calendar.solar2lunar(year, month, date);
                }else{
                    month = Number($(this).closest('.p2').find(".glMonth").val());
                    date = Number($(this).closest('.p2').find(".glDate").val());
                    lunar = calendar.lunar2solar(year, month, date);
                }
                lunar.hour = $(this).closest('.p2').find(".hour").val();
//					console.log(lunar);
                var result = WanNianLi.getResult(lunar);
//					console.log(result);
                $(this).closest('.p2').find("#cYear").html(lunar.cYear + '年');
                $(this).closest('.p2').find("#cMonth").html(lunar.cMonth + '月');
                $(this).closest('.p2').find("#cDay").html(lunar.cDay + '日');
                $(this).closest('.p2').find("#cHour").html(lunar.hour + '时');

                $(this).closest('.p2').find("#lYear").html(lunar.gzYear+ '年');
                $(this).closest('.p2').find("#lMonth").html(lunar.IMonthCn);
                $(this).closest('.p2').find("#lDay").html(lunar.IDayCn);
                $(this).closest('.p2').find("#lHour").html(result.bazi.hour.slice(1,2) + '时');

                $(this).closest('.p2').find("#bYear").html(result.bazi.year);
                $(this).closest('.p2').find("#bMonth").html(result.bazi.month);
                $(this).closest('.p2').find("#bDay").html(result.bazi.date);
                $(this).closest('.p2').find("#bHour").html(result.bazi.hour);

                $(this).closest('.p2').find("#wYear").html(result.wuxing.year);
                $(this).closest('.p2').find("#wMonth").html(result.wuxing.month);
                $(this).closest('.p2').find("#wDay").html(result.wuxing.date);
                $(this).closest('.p2').find("#wHour").html(result.wuxing.hour);

                var msg = msg1 = '';
                [].forEach.call(Object.keys(result.wuxingResult), function(value, index){
                    if(result.wuxingResult[value] === 0) {
                        msg += '<em>' + value + ': ' + result.wuxingResult[value] + '</em>' + ', ';
                        !msg1 && (msg1 = '<br/>五行缺：');
                        msg1 += value + ', ';
                    }else{
                        msg += value + ': ' + result.wuxingResult[value] + ', ';
                    }
                });
                msg = msg.slice(0, msg.length-2);
                if(msg1.length > 2) {
                    msg1 = msg1.slice(0, msg1.length-2);
                }
                $(this).closest('.p2').find("#info").html("五行：" + msg + msg1);

                $(this).closest('.p2').find(".result").show();
            });
        });
        function initDateSelect(){
            var i= len = 0, d = new Date();
            var lunar = calendar.solar2lunar(d.getFullYear(), d.getMonth() + 1, d.getDate());

            //年份
            for(i = 1950; i <= 2020; i++) {
                $(".year").append("<option value='" + i + "'>" + i + "</option>");
            }
            $(".year option[value=" + d.getFullYear() + "]").attr("selected", "selected");

            //公历月
            for(i = 1; i <= 12; i++) {
                $(".ylMonth").append("<option value='" + i + "'>" + i + "</option>");
            }
            $(".ylMonth option[value=" + (d.getMonth() + 1) + "]").attr("selected", "selected");

            //公历天
            for(i = 1; i <= 31; i++) {
                $(".ylDate").append("<option value='" + i + "'>" + i + "</option>");
            }
            $(".ylDate option[value=" + d.getDate() + "]").attr("selected", "selected");

            //时辰
            for(i = 00; i <= 23; i++) {
                $(".hour").append("<option value='" + i + "'>" + chinaTime[i] + ' ' + i + "</option>");
            }
            $(".hour option[value=" + d.getHours() + "]").attr("selected", "selected");

            //农历月
            for(i = 0, len = chinaMonth.length; i < len; i++){
                $(".glMonth").append("<option value='" + (i+1) + "'>" + chinaMonth[i] + "</option>");
            }
            $(".glMonth option[value=" + (lunar.lMonth) + "]").attr("selected", "selected");

            //农历天
            for(i = 0, len = chinaDate.length; i < len; i++){
                $(".glDate").append("<option value='" + (i+1) + "'>" + chinaDate[i] + "</option>");
            }
            $(".glDate option[value=" + (lunar.lDay) + "]").attr("selected", "selected");
        }
    </script>
    <div class="view-pc">
@foreach(MContentBlock::allCached($record['title'],50) as $cb)
            <?php if(empty($cb)) continue;?>
    @if($cb['model'] == 'p1')
        @if($record['title'] == 'Creative')
            <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
                <div class="one-pic-banner" style="background-image:url({{isset($cb['images'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0]):''}});background-position: center top;background-size: cover;">
                    <p>{{$cb['texts'][0]??'尋找您的本命香'}}</p>
                </div>
                <div class="text">
                    <p>{{$cb['texts'][1]??'一起走進香氣的世界'}}</p>
                    <p>{{$cb['texts'][2]??'感受日柱賦予的香氣魅力'}}</p>
                </div>
            </div>
        @else
            <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
                <div class="one-pic-banner" style="background-image:url({{isset($cb['images'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0]):''}});background-position: center top;background-size: cover;">


                    <p>{{$cb['texts'][0]??''}}</p>
                    <p>{{$cb['texts'][1]??''}}</p>
                    <button  class="banner-button">進一步了解</button>
                </div>
            </div>
        @endif

    @elseif($cb['model'] == 'p2')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}"  >
            <p class="text1">{{$cb['texts'][0]??'查找您的日柱'}}</p>
            <p class="text2">{{$cb['texts'][1]??'輸入您的出生日期'}}</p>
            <div class="one-pic-banner" style="background-image:url({{isset($cb['images'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0]):''}});background-position: center top;background-size: cover;">

                <div class="search-rzh">
                    <select class="type" id="type">
                        <option value="0" selected="selected">公历</option>
                        <option value="1">农历</option>
                    </select>
                    <select class="year" id="year"></select>年
                    <div class="gongli" id="gongli" style="display: inline-block;">
                        <select class="ylMonth" id="ylMonth"></select>月
                        <select class="ylDate" id="ylDate"></select>日
                    </div>
                    <div class="nongli" id="nongli" style="display: none;">
                        <select class="glMonth" id="glMonth"></select>
                        <select class="glDate" id="glDate"></select>
                    </div>
                    <select class="hour" id="hour"></select>时
                    <button class="go" id="go">查询五行八字</button>
                </div>
                <div class="result" id="result" style="display: none;">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <th>生日(公历)</th>
                            <td id="cYear"></td>
                            <td id="cMonth"></td>
                            <td id="cDay"></td>
                            <td id="cHour"></td>
                        </tr>
                        <tr>
                            <th>生日(农历)</th>
                            <td id="lYear"></td>
                            <td id="lMonth"></td>
                            <td id="lDay"></td>
                            <td id="lHour"></td>
                        </tr>
                        <tr>
                            <th>八字</th>
                            <td id="bYear"></td>
                            <td id="bMonth"></td>
                            <td id="bDay"></td>
                            <td id="bHour"></td>
                        </tr>
                        <tr>
                            <th>五行</th>
                            <td id="wYear"></td>
                            <td id="wMonth"></td>
                            <td id="wDay"></td>
                            <td id="wHour"></td>
                        </tr>
                        <tr style="height: 60px;">
                            <th>分析</th>
                            <td id="info" colspan="4"></td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        @elseif($cb['model'] == 'p3')
            <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
                <div class="one-pic-banner" style="background-image:url({{isset($cb['images'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0]):''}});background-position: center top;background-size: cover;">
                <p class="text1">{{$cb['texts'][0]??'訂製專屬日柱本命香水'}}</p>
                <p class="text2">{{$cb['texts'][1]??'日柱決定了你的命定本命香元素   這是你與生俱來的香氣'}}</p>

                </div>
            </div>

        @elseif($cb['model'] == 'p10')
            <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}" style="background-image:url({{\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0] ?? \ModStart\Core\Assets\AssetsUtil::fix('vendor/Site/image/more-bg1.png'))}});background-size: cover;">
                <div class=" one-pic-banner ">

                    <div class="more-text-o">
                        <div class="part1">
                            @foreach($cb['texts'] as $index=>$text)
                                <p>{{$text}}</p>
                            @endforeach
                        </div>
                        <div class="part2 banner-button">
                            <a><button class="contact">立即咨询</button></a>
                            <a><button class="more">了解更多</button></a>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($cb['model'] == 'p11')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}"  >
            @if (isset($cb['imagesMo'][0]))
                <div class="content-bg" style="background-image:url({{isset($cb['images'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0]):''}});background-position: center center;background-repeat:no-repeat;"></div>
            @endif
            <div class="content"  >

                @foreach($cb['texts'] as $index=>$text)
                    <div class="part">
                        <img src="@asset('vendor/Site/image/brand-logo-s.png')" alt="">
                        <?php $array = explode('|', $text);?>
                        <p>{{$array[0]??''}}</p>
                        @if(!empty($array[1]))
                            @foreach (explode('li:', $array[1]) as $key=>$li)
                            <p  class="li">{{$li}}</p>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($cb['model'] == 'p20')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
            <div class="head">
                @foreach($cb['texts'] as $index=>$text)
                    <p>{{$text}}</p>
                @endforeach
            </div>
            <div class="image-list">
                <div class="img-row">
                    @foreach($cb['images'] as $index=>$img)
                        <img src="{{$img}}" />
                    @endforeach
                </div>
                <div class="img-row">
                    @foreach($cb['images'] as $index=>$img)
                        <img src="{{$img}}" />
                    @endforeach
                </div>
            </div>
        </div>
    @elseif($cb['model'] == 'p30')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}} one-pic-banner  mars-more"

        >
            <div class="content-bg" style="background-image:url({{isset($cb['images'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['images'][0]):\ModStart\Core\Assets\AssetsUtil::fix('vendor/Site/image/more-bg.png')}});background-size: cover;"></div>
            <div class="more-text">
                <div>
                    @foreach($cb['texts'] as $index=>$text)
                        <p>{{$text}}</p>
                    @endforeach
                    <button>了解更多</button>
                </div>
            </div>
        </div>
    @elseif($cb['model'] == 'p40')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
            @if ($cb['remark'])
            <div class="remark">{{$cb['remark']}}</div>
            @endif
            <div class="head">
                @foreach($cb['texts'] as $index=>$text)
                    <div  class="{{$index==0?'on':'off'}}">{{$text}}</div>
                @endforeach
            </div>
            <div class="content">
                @foreach(MContentBlock::getCachedModel($record['title'], 'd40') as $key=>$model)
                    <?php if(empty($model)) continue;?>
                    <div class="part {{$key==0?'on':'off'}}">
                        <div class="part1">
                            @if (!empty($model['images']))
                            @foreach($model['images'] as $index=>$img)
                                @if($index==0)
                                    <img src="{{$img}}" />
                                @endif
                            @endforeach
                            @endif
                        </div>
                        <div class="part2">
                            @if (!empty($model['texts']))
                                <div>{{$model['texts'][0] ?? ''}}</div>
                                <div>{{$model['texts'][1] ?? ''}}</div>
                                @if(isset($model['texts'][2]))
                                <?php $array2 = explode('|', $model['texts'][2]);?>
                                <div class="h2 text">{{$array2[0] ?? ''}}</div>
                                <div class="text">{{$array2[1] ?? ''}}</div>
                                @endif
                                @if(isset($model['texts'][3]))
                                <?php $array3 = explode('|', $model['texts'][3]);?>
                                <div class="h2 text">{{$array3[0] ?? ''}}</div>
                                <div class="text">{{$array3[1] ?? ''}}</div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($cb['model'] == 'p41')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}} p40">
            @if ($cb['remark'])
                <div class="remark">{{$cb['remark']}}</div>
            @endif
            <div class="head">
                @foreach($cb['texts'] as $index=>$text)
                    <div  class="{{$index==0?'on':'off'}}">{{$text}}</div>
                @endforeach
            </div>
            <div class="content">
                @foreach(MContentBlock::getCachedModel($record['title'], 'd41') as $key=>$model)
                    <?php if(empty($model)) continue;?>
                    <div class="part {{$key==0?'on':'off'}}">
                        <div class="part2">
                            @if (!empty($model['texts']))
                                <div>{{$model['texts'][0] ?? ''}}</div>
                                <div>{{$model['texts'][1] ?? ''}}</div>
                                @if(isset($model['texts'][2]))
                                    <?php $array2 = explode('|', $model['texts'][2]);?>
                                    <div class="h2 text">{{$array2[0] ?? ''}}</div>
                                    <div class="text">{{$array2[1] ?? ''}}</div>
                                @endif
                                @if(isset($model['texts'][3]))
                                    <?php $array3 = explode('|', $model['texts'][3]);?>
                                    <div class="h2 text">{{$array3[0] ?? ''}}</div>
                                    <div class="text">{{$array3[1] ?? ''}}</div>
                                @endif
                            @endif
                        </div>
                        <div class="part1">
                            @if (!empty($model['images']))
                                @foreach($model['images'] as $index=>$img)
                                    @if($index==0)
                                        <img src="{{$img}}" />
                                    @endif
                                @endforeach
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    @elseif($cb['model'] == 'p42')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
            <div class="ub-container mars-swiper" style="background:#FFF;">
                {!! \Module\Banner\View\BannerView::basic('Brand1',null,'2-1') !!}
            </div>
        </div>
    @elseif($cb['model'] == 'p50')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
            <div class="part1">
                @if (!empty($cb['images']))
                    @foreach($cb['images'] as $index=>$img)
                        @if($index==0)
                            <img src="{{$img}}" />
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="part2">
                @if (!empty($cb['texts']))
                    @foreach($cb['texts'] as $index=>$text)
                        <div>{{$text}}</div>
                    @endforeach
                @endif
                <div class="banner-button">
                    <a><button class="more">進一步了解</button></a>
                    <a><button class="contact">立即咨询</button></a>
                </div>
            </div>
        </div>
    @elseif($cb['model'] == 'p60')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
            @if (!empty($cb['images']))
                @foreach($cb['images'] as $index=>$img)
                    @if($index==0)
                        <img src="{{$img}}" />
                    @endif
                @endforeach
            @endif
        </div>
    @elseif($cb['model'] == 'p70')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
            <div class="part1">
                @if (!empty($cb['texts']))
                    @foreach($cb['texts'] as $index=>$text)
                        <div>{{$text}}</div>
                    @endforeach
                @endif
            </div>
            <div class="part2 mars-cases cases-pc">
                @foreach(\MCms::listContentByCatUrl('cases',1,3,['where'=>['brand'=>$record['title']]]) as $index=>$case)
                    <div class="{{$index==0 ?"other-css":""}} cases-list">
                        <div class="case-info1">
                            <div class="mengban">
                                <img src="{{\ModStart\Core\Assets\AssetsUtil::fix($case['cover'])}}" alt="">
                            </div>
                            <div class="case-float">
                                <div class="case-text">
                                    <p class="case-title">{{$case['title']}}</p>
                                    <p  class="case-desc">{{$case['summary']}}</p>
                                    <a class="image" href="{{$case['_url']}}"><button>了解更多</button></a><span class="contact"><a>{{$index==0 ?"立即咨詢 >":""}}</a></span>
                                </div>
                            </div>
                        </div>
                        <div class="case-info2">
                            <div>
                                <img src="@asset('vendor/Site/image/cases-logo.png')" alt="">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mars-cases-m">
                @foreach(\MCms::listContentByCatUrl('cases',1,3,['where'=>['brand'=>$record['title']]]) as $index=>$case)
                    <div class="{{$index==0 ?"other-css":""}} cases-list">
                        <div class="case-info1">
                            <div class="mengban">
                                <img src="{{\ModStart\Core\Assets\AssetsUtil::fix($case['cover'])}}" alt="">
                            </div>
                            <div class="case-float">
                                <div class="case-text">
                                    <p class="case-title">{{$case['title']}}</p>
                                    <p  class="case-desc">{{$case['summary']}}</p>
                                    <a class="image" href="{{$case['_url']}}"><button>了解更多</button></a><span class="contact"><a>{{$index==0 ?"立即咨詢 >":""}}</a></span>
                                </div>
                            </div>
                        </div>
                        <div class="case-info2">
                            <div>
                                <img src="@asset('vendor/Site/image/cases-logo.png')" alt="">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($cb['model'] == 'p80')
        <div class="ub-container brand-part {{$record['title']}}  {{$cb['model']}}">
            @if (!empty($cb['texts']))
                <div class="content">

                    @foreach($cb['texts'] as $index=>$text)
                        @if($index <2)
                            <div class="h-part">{{$text}}</div>
                        @else
                            <?php $array = explode('|', $text);?>
                            <div class="part">
                                <div class="part1">{{$array[0]??''}} <img class="open"  src="@asset('vendor/Site/image/open.png')" alt=""></div>
                                <div class="part2">{{$array[1]??''}}</div>
                            </div>
                        @endif

                    @endforeach
                </div>
            @endif
        </div>
    @endif
@endforeach
    </div>
<div class="view-m">

    @foreach(MContentBlock::allCached($record['title'],50) as $cb)
        <?php if(empty($cb)) continue;?>
        @if($cb['model'] == 'p1')
            @if($record['title'] == 'Creative')
                <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">
                    <div class="one-pic-banner" style="background-image:url({{isset($cb['imagesMo'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['imagesMo'][0]):''}});background-position: center top;background-size: cover;">
                        <p>{{$cb['texts'][0]??'尋找您的本命香'}}</p>
                    </div>
                    <div class="text">
                        <p>{{$cb['texts'][1]??'一起走進香氣的世界'}}</p>
                        <p>{{$cb['texts'][2]??'感受日柱賦予的香氣魅力'}}</p>
                    </div>
                </div>
            @else
                <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">
                    <div class=" one-pic-banner index-brand ">
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
                </div>
            @endif
        @elseif($cb['model'] == 'p2')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}"  >
                <p class="text1">{{$cb['texts'][0]??'查找您的日柱'}}</p>
                <p class="text2">{{$cb['texts'][1]??'輸入您的出生日期'}}</p>
                <div class="search-rzh">
                    <div class="select">

                        <select class="type" id="type">
                            <option value="0" selected="selected">公历</option>
                            <option value="1">农历</option>
                        </select>
                        <img class=""  src="@asset('vendor/Site/image/vector.svg')" alt="" />
                    </div>
                    <div class="select">
                        <select class="year" id="year"></select>
                        <img  src="@asset('vendor/Site/image/vector.svg')" alt="" />
                    </div>
                    <div class="gongli" id="gongli" style="display: inline-block;">

                        <div class="select">
                            <select class="ylMonth" id="ylMonth"></select><img  src="@asset('vendor/Site/image/vector.svg')" alt="" />
                        </div>
                        <div class="select">
                            <select class="ylDate" id="ylDate"></select><img  src="@asset('vendor/Site/image/vector.svg')" alt="" />
                        </div>
                    </div>
                    <div class="nongli" id="nongli" style="display: none;">
                        <div class="select">
                            <select class="glMonth" id="glMonth"></select><img  src="@asset('vendor/Site/image/vector.svg')" alt="" />
                        </div>
                        <div class="select">
                            <select class="glDate" id="glDate"></select><img  src="@asset('vendor/Site/image/vector.svg')" alt="" />
                        </div>
                    </div>
                    <div class="select">
                        <select class="hour" id="hour"></select><img  src="@asset('vendor/Site/image/vector.svg')" alt="" />
                    </div>
                    <button class="go" id="go">查询五行八字</button>
                </div>
                <div class="one-pic-banner" style="background-image:url({{isset($cb['imagesMo'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['imagesMo'][0]):''}});background-position: center top;background-size: cover;">


                    <div class="result" id="result" style="display: none;">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <th>生日(公历)</th>
                                <td id="cYear"></td>
                                <td id="cMonth"></td>
                                <td id="cDay"></td>
                                <td id="cHour"></td>
                            </tr>
                            <tr>
                                <th>生日(农历)</th>
                                <td id="lYear"></td>
                                <td id="lMonth"></td>
                                <td id="lDay"></td>
                                <td id="lHour"></td>
                            </tr>
                            <tr>
                                <th>八字</th>
                                <td id="bYear"></td>
                                <td id="bMonth"></td>
                                <td id="bDay"></td>
                                <td id="bHour"></td>
                            </tr>
                            <tr>
                                <th>五行</th>
                                <td id="wYear"></td>
                                <td id="wMonth"></td>
                                <td id="wDay"></td>
                                <td id="wHour"></td>
                            </tr>
                            <tr style="height: 60px;">
                                <th>分析</th>
                                <td id="info" colspan="4"></td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        @elseif($cb['model'] == 'p3')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">
                <div class="one-pic-banner" style="background-image:url({{isset($cb['imagesMo'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['imagesMo'][0]):''}});background-position: center top;background-size: cover;">
                    <p class="text1">{{$cb['textsMo'][0]??'訂製專屬日柱本命香水'}}</p>
                    <p class="text2">{{$cb['textsMo'][1]??'日柱決定了你的命定本命香元素    '}}</p>
                    <p class="text2">{{$cb['textsMo'][2]??'這是你與生俱來的香氣'}}</p>

                </div>
            </div>
        @elseif($cb['model'] == 'p10')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">
                <div class=" one-pic-banner ">

                    @if(!empty($cb['imagesMo'])||!empty($cb['images']))
                        @foreach($cb['imagesMo']??$cb['images'] as $img)
                            <img src="{{$img}}" alt="">
                        @endforeach
                    @endif
                    <div class="more-text">
                        <div class=" ">
                            @foreach($cb['texts'] as $index=>$text)
                                <p>{{$text}}</p>
                            @endforeach
                        </div>
                        <div class=" banner-button">
                            <a><button class="contact">了解更多</button></a>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($cb['model'] == 'p11')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}"  >
                @if (isset($cb['imagesMo'][0]))
                    <div class="content-bg" style="background-image:url({{isset($cb['imagesMo'][0])?\ModStart\Core\Assets\AssetsUtil::fix($cb['imagesMo'][0]):''}});background-position: center center;background-repeat:no-repeat;"></div>
                @endif
                @foreach($cb['texts'] as $index=>$text)
                    <div class="part">
                        <img src="@asset('vendor/Site/image/brand-logo-s.png')" alt="">
                        <?php $array = explode('|', $text);?>
                        <p>{{$array[0]??''}}</p>
                        @if(!empty($array[1]))
                            @foreach (explode('li:', $array[1]) as $key=>$li)
                                <p  class="li">{{$li}}</p>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        @elseif($cb['model'] == 'p20')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">
                <div class="head">
                    @foreach($cb['texts'] as $index=>$text)
                        <p>{{$text}}</p>
                    @endforeach
                </div>
                <div class="image-list">
                    @foreach($cb['images'] as $index=>$img)
                        <img src="{{$img}}" />
                    @endforeach
                </div>
            </div>
        @elseif($cb['model'] == 'p30')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}} one-pic-banner  mars-more">

                <img src="{{$cb['imagesMo'][0] ?? \ModStart\Core\Assets\AssetsUtil::fix('vendor/Site/image/more-pic-m.png')}}" />
                <div class="more-text">

                    @foreach($cb['texts'] as $index=>$text)
                        <p>{{$text}}</p>
                    @endforeach

                    <div class="button"><button>了解更多</button></div>
                </div>
            </div>
        @elseif($cb['model'] == 'p40')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">
                <div class="example-css-tab">
                    <div class="container dwo">
                        <div class="card">
                            @foreach(MContentBlock::getCachedModel($record['title'], 'd40') as $key=>$model)
                                <?php if(empty($model)) continue;?>
                                <input type="radio" name="select" id="slide_{{$key+1}}" {{$key==0 ?'checked':''}} >
                            @endforeach
                            <input type="checkbox" id="slideImg">
                            <div class="slider">
                                @foreach(MContentBlock::getCachedModel($record['title'], 'd40') as $key=>$model)
                                    <?php if(empty($model)) continue;?>
                                    <label for="slide_{{$key+1}}" class="slide slide_{{$key+1}}"></label>
                                @endforeach
                            </div>
                            @foreach(MContentBlock::getCachedModel($record['title'], 'd40') as $key=>$model)
                                    <?php if(empty($model)) continue;?>
                                <div class="inner_part">

                                    <div class="content content_{{$key+1}}">

                                        <div class="content-inner ">
                                            @if (!empty($model['texts']))
                                                <div>{{$model['texts'][0] ?? ''}}</div>
                                                <div>{{$model['texts'][1] ?? ''}}</div>
                                                @if(isset($model['texts'][2]))
                                                    <?php $array2 = explode('|', $model['texts'][2]);?>
                                                    <div class="h2 text">{{$array2[0] ?? ''}}</div>
                                                    <div class="text">{{$array2[1] ?? ''}}</div>
                                                @endif
                                                @if(isset($model['texts'][3]))
                                                    <?php $array3 = explode('|', $model['texts'][3]);?>
                                                    <div class="h2 text">{{$array3[0] ?? ''}}</div>
                                                    <div class="text">{{$array3[1] ?? ''}}</div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
        @elseif($cb['model'] == 'p42')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">

                <div class="ub-m-container cms-banner" style="background:#FFF;">
                    {!! \Module\Banner\View\BannerView::basic('Brand1-m',null,'2-1', '2-1') !!}
                </div>
            </div>
        @elseif($cb['model'] == 'p50')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">

                <div class="part2">
                    @if (!empty($cb['texts']))
                        @foreach($cb['texts'] as $index=>$text)
                            <div>{{$text}}</div>
                        @endforeach
                    @endif
                    <div class="banner-button">
                        <a><button class="more">進一步了解</button></a>
                    </div>
                </div>
            </div>
        @elseif($cb['model'] == 'p60')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">
                @if(!empty($cb['imagesMo'])||!empty($cb['images']))
                    @foreach($cb['imagesMo']??$cb['images'] as $index=>$img)
                        <div>
                            <img src="{{$img}}" />
                        </div>
                    @endforeach
                @endif
            </div>
        @elseif($cb['model'] == 'p70')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">
                <div class="part1">
                    @if (!empty($cb['texts']))
                        @foreach($cb['texts'] as $index=>$text)
                            <div>{{$text}}</div>
                        @endforeach
                    @endif
                </div>
                <div class="part2 mars-cases">
                    @foreach(\MCms::listContentByCatUrl('cases',1,3,['where'=>['brand'=>$record['title']]]) as $case)
                        <div class="cases-list">
                            <div class="case-info1">
                                <img src="{{\ModStart\Core\Assets\AssetsUtil::fix($case['coverMo'])}}" alt="">
                                <div class="case-float">
                                    <div class="case-text">
                                        <p class="case-title">{{$case['title']}}</p>
                                        <p  class="case-desc">{{$case['summary']}}</p>
                                        <a class="image" href="{{$case['_url']}}"><button>了解更多</button></a>
                                    </div>
                                </div>
                            </div>
                            <div class="case-info2">
                                <div>
                                    <img src="@asset('vendor/Site/image/cases-logo.png')" alt="">
                                </div>
                                {{--                            <p>{{$record['title']}}</p>--}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($cb['model'] == 'p80')
            <div class="ub-m-container brand-part {{$record['title']}}  {{$cb['model']}}">
                @if (!empty($cb['texts']))
                    @foreach($cb['texts'] as $index=>$text)
                        @if($index <2)
                            <div class="h-part">{{$text}}</div>
                        @else
                            <?php $array = explode('|', $text);?>
                            <div class="part">
                                <div class="part1">{{$array[0]??''}}<img class="close" src="@asset('vendor/Site/image/close.png')" alt=""><img class="open"  src="@asset('vendor/Site/image/open.png')" alt=""></div>
                                <div class="part2">{{$array[1]??''}}</div>
                            </div>
                        @endif

                    @endforeach
                @endif
                {{--            <div class="brand-part-bg"></div>--}}
            </div>
        @endif
    @endforeach
</div>
<script>
    $(function () {
        var angle = 0;
        $('.p40 .head div').mouseover(function(e) {
            e.preventDefault();
            $(this).addClass('on');
            $(this).siblings().removeClass('on');
            $(this).closest('.p40').find('.content .part:eq('+$(this).index()+')').css('display', 'block');
            $(this).closest('.p40').find('.content .part:eq('+$(this).index()+')').siblings().css('display', 'none');
        });
        $('.p40 .head div').mouseenter(function(e){
            e.preventDefault();
            $(this).addClass('on');
            $(this).siblings().removeClass('on');
            $(this).closest('.p40').find('.content .part:eq('+$(this).index()+')').css('display', 'block');
            $(this).closest('.p40').find('.content .part:eq('+$(this).index()+')').siblings().css('display', 'none');
        });
        $('.Creative.p80 div.part .part1').click(function(e){
            e.preventDefault();
            $(this).addClass('on');
            $(this).parent().find('.part2').slideToggle();
            angle += 45;
            $(this).find('.open').css({
                'transform': 'rotate(' + angle + 'deg)',
                '-ms-transform': 'rotate(' + angle + 'deg)',
                '-webkit-transform': 'rotate(' + angle + 'deg)'
            });
        });
        $('.view-m .p40 .inner_part').each(function(index, ul){
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
                        if ((moveX-startX)  > 0) {
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
                    }

                }

            })
        });
    });
</script>
<style>
    .footer1{
        display:none;
    }
</style>
@include('module::CmsThemeMars.View.pc.footer2')
@endsection





