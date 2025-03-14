@extends($_viewFrame)

@section('pageTitleMain'){{$record['title']}}@endsection
@section('pageKeywords'){{$record['title']}}@endsection
@section('pageDescription'){{$record['title']}}@endsection

{!! \ModStart\ModStart::js('asset/common/lazyLoad.js') !!}
@section('bodyContent')

    <div class="ub-content">
        <div class="panel-a"
             style="background-image:{{modstart_config('$ModuleName$_Background')?'url('.\ModStart\Core\Assets\AssetsUtil::fix(modstart_config('$ModuleName$_Background')).')':'var(--color-primary-gradient-bg)'}};">
            <div class="box">
                <h1 class="title">
                    {{$record['title']}}
                </h1>
                <div class="sub-title">
                    {{$record['summary']}}
                </div>
            </div>
        </div>
    </div>

    <div class="ub-container margin-bottom">

        <div class="row">
            <div class="col-md-9">

                <div class="ub-panel margin-top" style="padding:1rem;">
                    <div class="ub-article">
                        <div class="content ub-html lg">
                            {!! \ModStart\Core\Util\HtmlUtil::replaceImageSrcToLazyLoad($record['content'],'data-src',true) !!}
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-3">

                <div class="ub-panel margin-top">
                    <div class="head">
                        <div class="title">
                            最近发布
                        </div>
                    </div>
                    <div class="body ub-list-items">
                        @foreach($latestRecords as $record)
                            <a class="item-c" href="{{modstart_web_url('$module_name$/show/'.$record['id'])}}">{{$record['title']}}</a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection





