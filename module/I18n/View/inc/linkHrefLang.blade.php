@foreach(\Module\I18n\Util\LangUtil::listAll() as $lang)
    @if($lang['shortName']!=L_locale())
        <link rel="alternate" href="{{\Module\I18n\Util\LangUtil::url($url,$lang)}}" hreflang="{{$lang['shortName']}}" />
    @endif
@endforeach
