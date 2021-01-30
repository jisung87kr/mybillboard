<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
class YoutubeDataApiController extends Controller
{
    public $clientSecret;
    public $order;
    public $maxResults;
    public function __construct()
    {
        $searchResponse = null;
        $this->clientSecret = config('services.youtubeData.web', compact('searchResponse'));
        $this->order = 'viewCount';
        $this->maxResults = '5';
    }

    public function index()
    {
        $searchResponse = json_decode(Storage::get('public/ytresponse.json'));
        return view('youtube.index', compact('searchResponse'));
    }

    public function search(Request $request)
    {
        $searchResponse = json_decode(Storage::get('public/ytresponse.json'));
        if($request->ajax()){
            $component = view('components.youtube-item-list')->with(compact('searchResponse'))->render();
            return response()->json($component);
        }

        return view('youtube.index', compact('searchResponse'));

//        $searchResponse = null;
//        $this->searchInit();
//
//        $searchResponse = $this->youtube->search->listSearch('id,snippet', array(
//            'q' => $request->q,
//            'order' => $this->order,
//            'maxResults' => $this->maxResults,
//        ));
//
//        $searchResponse = $searchResponse->items;
//        if($request->ajax()){
//            $component = view('components.youtube-item-list')->with(compact('searchResponse'))->render();
//            return response()->json($component);
//        }
//        return view('youtube.index', compact('searchResponse'));
    }

    public function searchInit()
    {
        $this->client = new \Google_Client();
        $this->client->setDeveloperKey($this->clientSecret['apiKey']);
        $this->youtube = new \Google_Service_YouTube($this->client);
    }

    public function storePlaylist(Request $request)
    {
        ddd($request->all());
        return false;
    }
}
