<?php

namespace App\Http\Controllers;

use App\Models\BillboardChart;
use App\Models\BillboardChartCategory;
use Illuminate\Http\Request;

class BillboardChartController extends Controller
{
    public $chartUrl;
    public $imageurl;
    public $category;

    public function __construct()
    {
        $this->chartUrl = "https://www.billboard.com/charts";
        $this->imageUrl = "https://charts-static.billboard.com";
        $this->category = BillboardChartCategory::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated     = $request->validate([
            'chart_category_key' => 'required',
            'chart_date'        => 'required',
            'title'             => 'required',
            'chart'             => 'required',
            'video'             => 'required',
        ]);
        $chartCategory = BillboardChartCategory::where('key', $request->input('chart_category_key'))->first();
        $validated['billboard_chart_category_id'] = $chartCategory['id'];
        unset($validated['chart_category_key']);
        $chartCategory->charts()->create($validated);
        return redirect()->route('index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\BillboardChart $chart
     * @return \Illuminate\Http\Response
     */
    public function show(BillboardChart $chart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\BillboardChart $chart
     * @return \Illuminate\Http\Response
     */
    public function edit(BillboardChart $chart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BillboardChart $chart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillboardChart $chart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\BillboardChart $chart
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillboardChart $chart)
    {
        //
    }

    /**
     * 빌보드차트 파싱
     */
    /**
     * @param $url
     * @param null $data
     * @return bool|string
     */
    public function curl($url, $data = null)
    {

        if ($data) {
            $url .= "?" . http_build_query($data, '',);
        }

        $ch = curl_init();                                 //curl 초기화
        curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function getLiveBillboardchart($param = null)
    {
        $url = $this->chartUrl;
        if ($param) {
            $url .= '/' . $param;
        } else {
            $url .= '/' . $this->category[0]->key;
        }
        $response = $this->curl($url);
        $dom      = new \DOMDocument;
        @$dom->loadHTML($response);
        $charts = $dom->getElementById('charts');
        $data   = [
            'chart_name'   => $charts->getAttribute('data-chart-name'),
            'chart_date'   => $charts->getAttribute('data-chart-date'),
            'chart'        => json_decode($charts->getAttribute('data-charts')),
            'chart_videos' => json_decode($charts->getAttribute('data-chart-videos')),
            'is_live'      => true,
        ];
        return $data;
    }
}
