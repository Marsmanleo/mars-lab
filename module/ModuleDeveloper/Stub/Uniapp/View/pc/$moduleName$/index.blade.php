<!DOCTYPE html>
<html lang=zh-CN>
<head>
    <script>
        window.__msCDN = '{{\ModStart\Core\Assets\AssetsUtil::cdn()}}';
        window.__msApi = '{{modstart_api_url()}}';
    </script>
    <style type="text/css">
        @if(modstart_config('sitePrimaryColor',null))
            :root, page{
            --theme-color-primary: {{modstart_config('sitePrimaryColor')}};
            --theme-color-primary-light: {{modstart_config('sitePrimaryColor')}};
            --theme-color-primary-dark: {{modstart_config('sitePrimaryColor')}};
        }
        @endif
    </style>
    @include('module::$ModuleName$.View.m.$moduleName$.head')
    {!! \ModStart\Core\Hook\ModStartHook::fireInView('MobilePageHeadAppend'); !!}
</head>
<body>
@include('module::$ModuleName$.View.m.$moduleName$.body')
{!! \ModStart\Core\Hook\ModStartHook::fireInView('MobilePageBodyAppend'); !!}
</body>
</html>
