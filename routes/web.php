<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BillboardChartController;
use App\Http\Controllers\youtubeDataApiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function(){
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::resource('/post', PostController::class);
Route::post('/chart', [BillboardChartController::class, 'store'])->name('chart.store');
//Auth::routes();

Route::get('/test', function(){
    return view('test');
})->name('test');

Route::get('/youtube', [youtubeDataApiController::class, 'index'])->name('youtube.index');
Route::get('/youtube/search', [youtubeDataApiController::class, 'search'])->name('youtube.search');
Route::any('/youtube/playlist', [youtubeDataApiController::class, 'storePlaylist'])->name('youtube.storePlaylist');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


