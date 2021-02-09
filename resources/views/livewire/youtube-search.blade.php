<div class="resultbox">
    <div data-action="{{ route('youtube.search') }}" class="form-youtube-search">
        <input type="text" name="q" value="{{$item['title']}} - {{ $item['artist_name'] }}">
        <input type="submit" class="bg-blue-500 text-white p-3 rounded btn-search" value="search">
    </div>
    <div class="result"></div>
</div>
