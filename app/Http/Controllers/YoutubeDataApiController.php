<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class YoutubeDataApiController extends Controller
{
    public $clientSecret;
    public function __construct()
    {
        $searchResponse = null;
        $this->clientSecret = config('services.youtubeData.web', compact('searchResponse'));
    }

    public function index()
    {
        $searchResponse = null;
        return view('youtube.index', compact('searchResponse'));
    }

    public function search(Request $request)
    {
        $searchResponse = null;
        if(!isset($request->q)){
            return redirect()->route('youtube.index', compact('searchResponse'));
        }
        $this->searchInit();
        $searchResponse = $this->youtube->search->listSearch('id,snippet', array(
            'q' => $request->q,
            'order' => $request->order,
            'maxResults' => $request->maxResults,
        ));

        $searchResponse = $searchResponse->items;
        return view('youtube.index', compact('searchResponse'));
//        return redirect()->route('youtube.index', compact('searchResponse'));
    }

    public function searchInit()
    {
        $this->client = new \Google_Client();
        $this->client->setDeveloperKey($this->clientSecret['apiKey']);
        $this->youtube = new \Google_Service_YouTube($this->client);
    }
}
