@extends('modstart::admin.dialogFrame')

@section('pageTitle'){{$moduleInfo['title']}} 工具类文档@endsection

@section('headAppend')
    @parent
    <style type="text/css">
        body {
            background: #EEE;
        }
    </style>
@endsection

@section('body')

    <div style="background-color:var(--color-primary);">
        <div class="ub-container">
            <div
                class="tw-py-10 tw-px-10 lg:tw-px-0 tw-bg-cover tw-bg-center tw-bg-no-repeat tw-text-center tw-text-white">
                <h1 class="tw-text-3xl tw-opacity-90">{{$moduleInfo['title']}}工具类文档</h1>
                <div class="tw-text-normal tw-opacity-60">
                    版本：v{{$moduleInfo['version']}}
                </div>
            </div>
        </div>
    </div>

    <div class="ub-container">
        <div class="ub-breadcrumb" style="margin:0 0.5rem;">
            <a href="{{modstart_web_url('m/'.$moduleInfo['name'])}}">{{$moduleInfo['title']}}</a>
            <a class="active" href="javascript:;">工具类文档</a>
        </div>
        <div>
            <div class="row">
                <div class="col-md-3">
                    <div>
                        <div class="ub-menu" style="max-height:calc( 100vh - 80px );top:70px;overflow:auto;"
                             id="leftMenu">
                            @foreach($utilContent as $cls)
                                <div class="title">{{$cls['title']}}</div>
                                <div class="items" style="display:block;">
                                    @foreach($cls['methods'] as $method)
                                        <a href="#{{urlencode($cls['name'].'::'.$method['name'])}}">{{$method['title']}}</a>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <script>
                        $(function () {
                            var $leftMenu = $('#leftMenu');
                            if ($(window).width() > 800) {
                                $leftMenu.css('width', $leftMenu.width() + 'px');
                                var offset = $leftMenu.offset();

                                function scroll() {
                                    var top = $(window).scrollTop();
                                    if (top > offset.top) {
                                        $leftMenu.css({position: 'sticky'});
                                    } else {
                                        $leftMenu.css({position: 'static'});
                                    }
                                }

                                $(window).on('scroll', scroll);
                                scroll();
                            }
                        });
                    </script>
                    @foreach($utilContent as $cls)
                        <div class="ub-panel">
                            <div class="head">
                                <div class="title">{{$cls['title']}}</div>
                            </div>
                            <div class="body ub-html lg">
                                @foreach($cls['methods'] as $method)
                                    <div>
                                        <a id="{{urlencode($cls['name'].'::'.$method['name'])}}"
                                           name="{{urlencode($cls['name'].'::'.$method['name'])}}"></a>
                                        <h3>{{$method['title']}}</h3>
                                        @if(!empty($method['desc']))
                                            <div class="tw--mt-4 tw-pb-2">{{$method['desc']}}</div>
                                        @endif
                                        <div class="tw-font-mono tw-bg-gray-100 tw-p-2 tw-rounded">
                                            {{$cls['name'].'::'.$method['name']}}
                                            ( <?php echo join(', ', array_map(function ($i) {
                                                return $i['name'];
                                            }, $method['params'])); ?> )
                                        </div>
                                        @if(!empty($method['params']))
                                            <table class="tw-w-full">
                                                <thead>
                                                <tr>
                                                    <th>参数</th>
                                                    <th>类型</th>
                                                    <th>说明</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($method['params'] as $param)
                                                    <tr>
                                                        <td width="150" class="tw-font-mono">{{$param['name']}}</td>
                                                        <td width="100">{{$param['type']}}</td>
                                                        <td>{{$param['desc']}}</td>
                                                    </tr>
                                                @endforeach
                                                @if(!empty($method['paramExample']))
                                                    <tr>
                                                        <td colspan="3">
                                                            <pre style="border:none;margin:0;white-space:pre-wrap;"
                                                                 class="brush:php">{{$method['paramExample']}}</pre>
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        @endif
                                        @if(!empty($method['return']) && $method['return']['type']!='void')
                                            <table class="tw-w-full">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        返回
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        {{$method['return']['type']}} {{$method['return']['desc']}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                        @if(!empty($method['returnExample']))
                                            <table class="tw-w-full">
                                                <thead>
                                                <tr>
                                                    <th>返回示例</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="padding:0;">
                                                        <div>
                                                            <pre style="border:none;margin:0;white-space:pre-wrap;"
                                                                 class="brush:json">{{$method['returnExample']}}</pre>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                @endforeach
                                @if(!empty($method['example']))
                                    <div>
                                        <div>示例代码</div>
                                        <div>
                                            <pre class="brush:php">{{$method['example']}}</pre>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
