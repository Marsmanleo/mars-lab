<div class="line" id="{{$id}}">
    <div class="label">
        {!! in_array('required',$rules)?'<span class="ub-text-danger ub-text-bold">*</span>':'' !!}
        {{$label}}:
    </div>
    <div class="field">
        <input type="text"
               {{$readonly?'readonly':''}}
               class="form"
               name="{{$name}}"
               placeholder="{{$placeholder}}"
               @if(null===$fixedValue)
               value="{{$value}}"
               @else
               value="{{$fixedValue}}"
               @endif
               @if($styleFormField) style="{!! $styleFormField !!}" @endif
        />
        @if(!empty($help))
            <div class="help">{!! $help !!}</div>
        @endif
        <div class="tw-p-4 tw-bg-gray-100 tw-rounded-lg">
            字段编辑可用值
            <table class="ub-table border tw-bg-white">
                <thead>
                <tr>
                    <th width="200">变量</th>
                    <th width="200">当前值</th>
                    <th>含义</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><code>$label</code></td>
                    <td>{{$label}}</td>
                    <td>当前字段名称</td>
                </tr>
                <tr>
                    <td><code>$name</code></td>
                    <td>{{$name}}</td>
                    <td>字段标识</td>
                </tr>
                <tr>
                    <td><code>$value</code></td>
                    <td>{{$value}}</td>
                    <td>值</td>
                </tr>
                <tr>
                    <td><code>$id</code></td>
                    <td>{{$id}}</td>
                    <td>唯一标识</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span class="ub-text-muted">更多请参考文件 <code>module/CustomFieldDemo/View/field/customField1.blade.php</code></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
