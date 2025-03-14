@extends($_viewFrame)

@section('pageTitleMain'){{modstart_config('$ModuleName$_Title','$ModuleTitle$')}}@endsection

@section('bodyContent')


    <div class="ub-content">
        <div class="panel-a"
             style="background-image:{{modstart_config('$ModuleName$_Background')?'url('.\ModStart\Core\Assets\AssetsUtil::fix(modstart_config('$ModuleName$_Background')).')':'var(--color-primary-gradient-bg)'}};">
            <div class="box">
                <h1 class="title">
                    {{modstart_config('$ModuleName$_Title','$ModuleTitle$')}}
                </h1>
                <div class="sub-title">
                    {{modstart_config('$ModuleName$_SubTitle','提供有价值的$ModuleTitle$')}}
                </div>
            </div>
        </div>
    </div>

    <div class="ub-container margin-bottom">

        <div class="row">
            <div class="col-md-9">
                <div class="ub-panel">
                    <div class="head">
                        <div class="title">
                            @if($categoryId)
                                @foreach($categories as $category)
                                    @if($category['id']==$categoryId)
                                        {{modstart_config('$ModuleName$_Title','$ModuleTitle$')}}
                                        <i class="iconfont icon-angle-right ub-text-muted"></i>
                                        {{$category['title']}}
                                    @endif
                                @endforeach
                            @else
                                {{modstart_config('$ModuleName$_Title','$ModuleTitle$')}}
                            @endif
                        </div>
                    </div>
                    <div class="body">
                        @if(empty($records))
                            <div class="ub-empty tw-my-20">
                                <div class="icon">
                                    <div class="iconfont icon-empty-box"></div>
                                </div>
                                <div class="text">暂无记录</div>
                            </div>
                        @endif
                        <div class="ub-list-items" style="padding:0.5rem;">
                            @foreach($records as $record)
                                <div class="item-k">
                                    <a class="image" href="{{modstart_web_url('$module_name$/show/'.$record['id'])}}">
                                        <div class="cover ub-cover-4-3" style="background-image:url({{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}})"></div>
                                    </a>
                                    <a class="title" href="{{modstart_web_url('$module_name$/show/'.$record['id'])}}">{{$record['title']}}</a>
                                    <div class="summary">
                                        {{\ModStart\Core\Util\HtmlUtil::text($record['summary'],200)}}
                                    </div>
                                    <div class="info">
                                        <div class="left">
                                            @if(!empty($record['_category']))
                                                <i class="iconfont icon-category"></i>
                                                <a href="{{modstart_web_url('$module_name$',['categoryId'=>$record['categoryId']])}}">{{$record['_category']['title']}}</a>
                                            @endif
                                        </div>
                                        <div class="right">
                                            <i class="iconfont icon-time"></i>
                                            {{\Carbon\Carbon::parse($record['updated_at'])->toDateString()}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="ub-page">
                            {!! $pageHtml !!}
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-3">

                <div class="ub-menu simple">
                    <a class="title @if(!$categoryId) active @endif" href="{{modstart_web_url('$module_name$/list')}}">全部</a>
                    @foreach($categories as $category)
                        <a class="title @if($category['id']==$categoryId) active @endif" href="{{modstart_web_url('$module_name$/list',['categoryId'=>$category['id']])}}">{{$category['title']}}</a>
                    @endforeach
                </div>

            </div>
        </div>

    </div>
@endsection
