<div class="ub-content-box margin-bottom">
    @foreach(\Module\I18n\Util\LangUtil::listAll() as $l)
        <a href="?{{\ModStart\Core\Input\Request::mergeQueries(['lang'=>$l['shortName']])}}"
           class="btn btn-round @if($l['shortName']==$lang) btn-primary @endif">
            <img src="{{$l['image']}}" class="tw-h-3 tw-rounded-sm" />
            {{$l['name']}}
        </a>
    @endforeach
</div>
