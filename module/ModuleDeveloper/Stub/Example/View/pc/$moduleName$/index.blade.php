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
        <div class="ub-content-box">
            <a class="btn btn-primary btn-round btn-lg" href="{{modstart_web_url('$module_name$/list')}}">
                <i class="iconfont icon-list"></i>
                列表页
            </a>
            <a class="btn btn-primary btn-round btn-lg" href="{{modstart_web_url('$module_name$/show/1')}}">
                <i class="iconfont icon-eye"></i>
                详情页
            </a>
        </div>
    </div>
@endsection
