<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $category;
    public $chartUrl;
    public $imageUrl;

    public function __construct()
    {
        $this->chartUrl = "https://www.billboard.com/charts";
        $this->imageUrl = "https://charts-static.billboard.com";
        $this->category = [
            'hot-100',
            'billboard-200',
            'billboard-global-200',
            'billboard-global-excl-us',
            'artist-100',
        ];
    }

    public function index(Request $request)
    {
        $cate = isset($request->cate) ? $request->cate : null;
        $data = [
            'category' => $this->category,
            'chart'     => $this->getBillboardchart($cate),
            'links'    => [
                'chart' => $this->chartUrl,
                'image' => $this->imageUrl,
            ],
        ];

        return view('post.index', compact('data'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }


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

    public function getBillboardchart($param = null)
    {
        $url = $this->chartUrl;
        if ($param) {
            $url .= '/' . $param;
        } else {
            $url .= '/' . $this->category[0];
        }
        $response = $this->curl($url);
        $dom      = new \DOMDocument;
        @$dom->loadHTML($response);
        $charts = $dom->getElementById('charts');
        $data = [
            'chart_name'   => $charts->getAttribute('data-chart-name'),
            'chart_date'   => $charts->getAttribute('data-chart-date'),
            'chart'        => json_decode($charts->getAttribute('data-charts')),
            'chart_videos' => json_decode($charts->getAttribute('data-chart-videos')),
        ];
        return $data;
    }
}
