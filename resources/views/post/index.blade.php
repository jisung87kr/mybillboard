@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="">
            <form action="{{ route('chart.store') }}" class="text-center row" method="POST">
                @csrf
                @method("POST")
                <div class="col-2">
                    <select name="chart_category_id" id="billboard-category" class="form-control" onchange="getChart(event)">
                        @foreach($data['category'] as $item)
                            <option value="{{$item->id}}" @if($item->key == app('request')->cate) selected @endif>{{ $item->key }}</option>
                        @endforeach
                    </select>
                    <script>
                        function getChart(e){
                            window.location = "?cate="+e.target.value;
                        }
                    </script>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-3"></div>
                <div class="item-status col-5">
                    <div class="row text-center small">
                        <div class="col-3">RE ENTRY</div>
                        <div class="col-3">PEAK</div>
                        <div class="col-3">LAST WEEK</div>
                        <div class="col-3">WRK ON CHART</div>
                    </div>
                </div>
                <div class="col-2">
                    <input type="hidden" name="title" value="{{ $data['chart']['chart_name'] }}">
                    <input type="hidden" name="chart_date" value="{{ $data['chart']['chart_date'] }}">
                    <input type="hidden" name="chart" value="{{ json_encode($data['chart']['chart']) }}">
                    <input type="hidden" name="video" value="{{ json_encode($data['chart']['chart_videos']) }}">
                    <input type="submit" value="STORE CHART" class="btn btn-primary">
                </div>
            </div>
        </form>
        <div class="chart">
            @foreach($data['chart']['chart'] as $item)
            <div class="row chart-item bg-white px-3 py-5 my-3">
                <input type="hidden" name="artist_id" value="{{ $item->artist_id }}">
                <div class="rankbox div-table-td col-1 text-center">
                    <div class="rank h3">{{ $item->rank }}</div>
                    <div class="updown text-muted">
                        @if($item->bullets->bullet_desc == true)
                        <span class="text-success">UP</span>
                        @else
                        <span class="text-danger">DOWN</span>
                        @endif
                    </div>
                </div>
                <div class="item-content col-4">
                    <div class="title h1"><b>{{ $item->title }}</b></div>
                    <div class="artist h5">{{ $item->artist_name }}</div>
                </div>
                <div class="item-status col-5">
                    <div class="row text-center">
                        <div class="col-3">{{ $item->history->re_entry }}</div>
                        <div class="col-3">{{ $item->history->peak_rank }}</div>
                        <div class="col-3">{{ $item->history->last_week }}</div>
                        <div class="col-3">{{ $item->history->weeks_on_chart }}</div>
                    </div>
                </div>
                <div class="item-image col-2 text-center">
                    @if(isset($item->title_images->sizes->{"ye-landing-lg"}->Name))
                    <img src="https://charts-static.billboard.com{{ $item->title_images->sizes->{"medium"}->Name }}" alt="" class="mw-100 m-auto">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection