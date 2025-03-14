@extends($_viewFrame)

@section('bodyContent')


    <div class="ub-container margin-bottom margin-top">

        <div class="ub-content-box">
            <pre>{{json_encode($category,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)}}</pre>
        </div>

    </div>

@endsection





