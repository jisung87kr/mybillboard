<x-app-layout>
@include('layouts.slot_header')
<div class="container max-w-7xl mx-auto p-4 sm:px-6 lg:px-8">
    <div class="">
        <form action="{{ route('chart.store') }}" class="grid grid-cols-8" method="POST">
            @csrf
            @method("POST")
            <div class="col-span-1">
                <select name="chart_category_key" id="billboard-category" class="form-control" onchange="getChart(event)">
                    @foreach($data['category'] as $item)
                        <option value="{{$item->key}}" data-id="{{ $item->id }}" @if($item->key == app('request')->cate) selected @endif>{{ $item->key }}</option>
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
            <div class="item-status col-start-4 col-span-4">
                <div class="row text-center small grid grid-cols-4">
                    <div class="">RE ENTRY</div>
                    <div class="">PEAK</div>
                    <div class="">LAST WEEK</div>
                    <div class="">WRK ON CHART</div>
                </div>
            </div>
            @if(isset($data['chart']['is_live']))
            <div class="col-span-1 text-center">
                <input type="hidden" name="title" value="{{ $data['chart']['chart_name'] }}">
                <input type="hidden" name="chart_date" value="{{ $data['chart']['chart_date'] }}">
                <input type="hidden" name="chart" value="{{ json_encode($data['chart']['chart']) }}">
                <input type="hidden" name="video" value="{{ json_encode($data['chart']['chart_videos']) }}">
                <input type="submit" value="STORE CHART" class="bg-blue-600 rounded p-3 text-white font-bold">
            </div>
            @endif
        </form>
    </div>
    <div class="chart">
        @foreach($data['chart']['chart'] as $key => $item)
            <div x-data="{ open: false }" class="chart-item bg-white px-3 py-5 my-3">
                <div class="grid grid-cols-8">
                    <input type="hidden" name="artist_id" value="{{ $item->artist_id }}">
                    <div class="rankbox col-span-1 div-table-td text-center">
                        <div class="rank h3">{{ $item->rank }}</div>
                        <div class="updown text-muted">
                            @if($item->bullets->bullet_desc == true)
                                <span class="text-green-500">UP</span>
                            @else
                                <span class="text-red-500">DOWN</span>
                            @endif
                        </div>
                    </div>
                    <div class="item-content col-span-2">
                        <div class="title text-xl"><b>{{ $item->title }}</b></div>
                        <div class="artist text-l">{{ $item->artist_name }}</div>
                    </div>
                    <div class="item-status col-span-4">
                        <div class="row text-center grid grid-cols-4">
                            <div class="">{{ $item->history->re_entry }}</div>
                            <div class="">{{ $item->history->peak_rank }}</div>
                            <div class="">{{ $item->history->last_week }}</div>
                            <div class="">{{ $item->history->weeks_on_chart }}</div>
                        </div>
                    </div>

                    <div class="item-image col-span-1 text-center">
                        @if(isset($item->title_images->sizes->{"ye-landing-lg"}->Name))
                            <img src="https://charts-static.billboard.com{{ $item->title_images->sizes->{"medium"}->Name }}" alt="" class="mw-100 m-auto">
                        @endif
                    </div>
                </div>
                <livewire:youtube-search :item="$item" key="$key"/>
            </div>
        @endforeach
    </div>
</div>
</x-app-layout>