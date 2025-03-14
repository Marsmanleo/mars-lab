@extends($_viewFrame)

@section('bodyContent')


    <div class="ub-container margin-bottom margin-top">

        @include('module::I18nDemo.View.pc.i18n.demo.common')

        <div class="ub-content-box">
            <pre>{{json_encode($categories,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)}}</pre>
        </div>

    </div>

@endsection





