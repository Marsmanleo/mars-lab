<div class="ub-content-box margin-bottom">
    <table class="ub-table">
        <thead>
            <tr>
                <th>变量</th>
                <th>说明</th>
                <th>示例</th>
            </tr>
        </thead>
        <tr>
            <td><code>$_lang</code></td>
            <td>当前语言信息</td>
            <td><pre>{{json_encode($_lang,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)}}</pre></td>
        </tr>
        <tr>
            <td><code>$_langShortName</code></td>
            <td>当前语言简称</td>
            <td>{{$_langShortName}}</td>
        </tr>
        <tr>
            <td><code>$_langs</code></td>
            <td>所有语言信息</td>
            <td><pre>{{json_encode($_langs,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)}}</pre></td>
        </tr>
    </table>
</div>
