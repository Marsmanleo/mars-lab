@extends($_viewFrame)

@section('pageTitleMain'){{$cat['seoTitle']?$cat['seoTitle']:$cat['title']}}@endsection
@section('pageKeywords'){{$cat['seoKeywords']?$cat['seoKeywords']:$cat['title']}}@endsection
@section('pageDescription'){{$cat['seoDescription']?$cat['seoDescription']:$cat['title']}}@endsection

@section('bodyContent')

     <div class="brands-main brands-m">
         @foreach($records as $key=>$record)
             <?php $show= $key; if(empty($_GET['br']) || $_GET['br']==$record['title']) break; ?>
         @endforeach
         <div class="br-left brands-content">
             @foreach($records as $key=>$record)
             <div class="the-brand1 {{ $show==$key ? 'br-show' : 'br-hide' }}">
                 <div class="desc-brand1 br-left">
                     <div class="br-name"><img src="{{\ModStart\Core\Assets\AssetsUtil::fix($record['cover'])}}" alt="">
                          {{$record['title']}}
                     </div>
                     <div class="br-title">{{$record['summary']}}</div>
                     <div class="br-desc">{{$record['desc']}} </div>
                     <div class="br-btn"><a href="{{$record['_url']}}"><button>了解更多</button></a> <span>立即咨詢 > </span></div>
                 </div>
             </div>
             @endforeach
         </div>

     </div>

@endsection





