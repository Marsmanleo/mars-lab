<table class="ub-table mini border hover">
    @foreach($langList as $lang=>$langTitle)
        <tr>
            <td width="120"><span class="ub-tag">{{$langTitle}}</span></td>
            <td>
                @if(empty($recordMap[$lang]))
                    <span class="ub-text-muted">[无]</span>
                @else
                    @if(isset($recordMap[$lang]['_gridItemRender']))
                        {!! $recordMap[$lang]['_gridItemRender'] !!}
                    @else
                        {{$recordMap[$lang]['title']}}
                    @endif
                @endif
            </td>
            <td width="120">
                @if(empty($recordMap[$lang]))
                    <a href="javascript:;" class="ub-lister-action"
                       data-dialog-request="{{$urlBase}}/add?dataId={{$item->id}}&lang={{$lang}}"
                       data-dialog-width="80%" data-dialog-height="80%">增加</a>
                @else
                    <a href="javascript:;" class="ub-lister-action"
                       data-dialog-request="{{$urlBase}}/edit?_id={{$recordMap[$lang]['id']}}"
                       data-dialog-width="80%" data-dialog-height="80%">编辑</a>
                    <a href="javascript:;" class="ub-lister-action ub-text-danger"
                       data-confirm="确定删除？" data-ajax-request-loading
                       data-ajax-request="{{$urlBase}}/delete?_id={{$recordMap[$lang]['id']}}">删除</a>
                @endif
            </td>
        </tr>
    @endforeach
</table>
