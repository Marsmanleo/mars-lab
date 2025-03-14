@extends($_viewFrame)

@section('pageTitleMain')关于博主@endsection
@section('pageKeywords')关于博主@endsection
@section('pageDescription')关于博主@endsection

@section('bodyContent')

    <div class="ub-alert warning tw-text-center margin-top">
        来自主题 $ModuleName$ 的视图
    </div>

    <div class="ub-container">
        <div class="row">
            <div class="col-md-8">

                <div class="tw-p-6 margin-top tw-bg-white tw-rounded">
                    <div class="tw-text-lg">
                        <i class="iconfont icon-user"></i>
                        关于我
                    </div>
                    <div class="tw-mt-4">
                        <div class="ub-html lg">
                            {!! modstart_config('Blog_AboutContent') !!}
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">

                @include('module::$ModuleName$.View.pc.blog.inc.info')

                @include('module::$ModuleName$.View.pc.blog.inc.categories')

                @include('module::$ModuleName$.View.pc.blog.inc.blogLatest')

            </div>
        </div>
    </div>

@endsection
