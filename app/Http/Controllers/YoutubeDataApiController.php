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
//        $searchResponse = json_decode(Storage::get('public/ytresponse.json'));
        $searchResponse = null;
        return view('youtube.index', compact('searchResponse'));
    }

    public function search(Request $request)
    {
// 테스트용 코드
//        $searchResponse = json_decode(Storage::get('public/ytresponse.json'));
//        if($request->ajax()){
//            $component = view('components.youtube-item-list')->with(compact('searchResponse'))->render();
//            return response()->json($component);
//        }
//
//        return view('youtube.index', compact('searchResponse'));
// 실제 서비스 할때 이 코드 사용
        $searchResponse = null;
        $this->searchInit();

        $searchResponse = $this->youtube->search->listSearch('id,snippet', array(
            'q' => $request->q,
            'order' => $this->order,
            'maxResults' => $this->maxResults,
        ));

        $searchResponse = $searchResponse->items;
        if($request->ajax()){
            $component = view('components.youtube-item-list')->with(compact('searchResponse'))->render();
            return response()->json($component);
        }
        return view('youtube.index', compact('searchResponse'));
    }

    public function searchInit()
    {
        $this->client = new \Google_Client();
        $this->client->setDeveloperKey($this->clientSecret['apiKey']);
        $this->youtube = new \Google_Service_YouTube($this->client);
    }

    public function storePlaylist(Request $request)
    {
        if(!$request->videoId){
//            return redirect()->back()->with('massage', '비디오값이 비었습니다.');
        }
        $client = new \Google_Client();
        $client->setClientId($this->clientSecret['client_id']);
        $client->setClientSecret($this->clientSecret['client_secret']);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = filter_var('http://localhost:9999/youtube/playlist', FILTER_SANITIZE_URL);
        $client->setRedirectUri($redirect);
        $youtube = new \Google_Service_YouTube($client);
        $client->prepareScopes();
        $tokenSessionKey = 'token-' . $client->prepareScopes();

        if (isset($request->code)) {
            if (strval($request->session()->get('state')) !== strval($request->state)) {
                die('The session state did not match.');
            }

            $client->authenticate($request->code);
            $request->session()->put($tokenSessionKey, $client->getAccessToken());
            return redirect()->refresh();
        }

        if ($request->session()->get($tokenSessionKey)) {
            $client->setAccessToken($request->session()->get($tokenSessionKey));
        }

        if($client->getAccessToken()){
            try {
                // This code creates a new, private playlist in the authorized user's
                // channel and adds a video to the playlist.
                // 1. Create the snippet for the playlist. Set its title and description.
                $title = "billboard ".$request->title." ".$request->chart_date;
                $playlistSnippet = new \Google_Service_YouTube_PlaylistSnippet();
                $playlistSnippet->setTitle($title);
                $playlistSnippet->setDescription($title);

                // 2. Define the playlist's status.
                $playlistStatus = new \Google_Service_YouTube_PlaylistStatus();
                $playlistStatus->setPrivacyStatus('private');

                // 3. Define a playlist resource and associate the snippet and status
                // defined above with that resource.
                $youTubePlaylist = new \Google_Service_YouTube_Playlist();
                $youTubePlaylist->setSnippet($playlistSnippet);
                $youTubePlaylist->setStatus($playlistStatus);

                // 4. Call the playlists.insert method to create the playlist. The API
                // response will contain information about the new playlist.
                $playlistResponse = $youtube->playlists->insert('snippet,status',
                    $youTubePlaylist, array());
                $playlistId = $playlistResponse['id'];

                // 5. Add a video to the playlist. First, define the resource being added
                // to the playlist by setting its video ID and kind.
                $playlist = [];
                foreach ($request->videoId as $index => $item) {
                    $resourceId = new \Google_Service_YouTube_ResourceId();
                    $resourceId->setVideoId($item);
                    $resourceId->setKind('youtube#video');
                    // Then define a snippet for the playlist item. Set the playlist item's
                    // title if you want to display a different value than the title of the
                    // video being added. Add the resource ID and the playlist ID retrieved
                    // in step 4 to the snippet as well.
                    $playlistItemSnippet = new \Google_Service_YouTube_PlaylistItemSnippet();
                    $playlistItemSnippet->setTitle($title);
                    $playlistItemSnippet->setPlaylistId($playlistId);
                    $playlistItemSnippet->setResourceId($resourceId);

                    // Finally, create a playlistItem resource and add the snippet to the
                    // resource, then call the playlistItems.insert method to add the playlist
                    // item.
                    $playlistItem = new \Google_Service_YouTube_PlaylistItem();
                    $playlistItem->setSnippet($playlistItemSnippet);
                    $playlistItemResponse = $youtube->playlistItems->insert(
                        'snippet,contentDetails', $playlistItem, array());
                    $playlist[] = $playlistItemResponse;
                }

                return view('youtube.report', compact('playlist'));
            } catch (Google_Service_Exception $e) {
                ddd($e->getMessage());
            } catch (Google_Exception $e) {
                ddd($e->getMessage());
            }
        } else {
            $state = mt_rand();
            $client->setState($state);
            $request->session()->put('state', $state);
            $authUrl = $client->createAuthUrl();
            return view('youtube.authorization', compact('authUrl'));
        }

        return false;
    }
}
