<x-app-layout>
    <x-slot name="header">
        header
    </x-slot>
    <div class="container mx-auto py-3">
        <form action="{{ route('youtube.search') }}" method="GET" class="grid-cols-1">
            @csrf
            <h1 class="text-center text-2xl">유투브 검색</h1>
            <div class="text-center mt-4">
                <input type="hidden" name="maxResults" valaue="3">
                <input type="hidden" name="order" value="viewCount">
                <input type="search"
                       name="q" id="q" class="border-blue-600 rounded p-3" placeholder="" aria-describedby="helpId">
                <input type="submit" value="search" class="p-3 rounded bg-blue-600 text-white">
            </div>
        </form>
        @if($searchResponse)
            <ul class="my-3">
                @foreach($searchResponse as $item)
                    <li class="list-none grid grid-cols-6 p-3 gap-3 bg-white mb-3 rounded border-gray-500 shadow">
                        <div class="col-span-1">
                            <img src="{{ $item->snippet->thumbnails->high->url }}" alt="" class="w-100">
                        </div>
                        <div class="col-span-5">
                            <a href="https://youtube.com/watch?v={{ $item->id->videoId }}">{{ $item->snippet->title }}</a>
                            <hr class="my-2">
                            <p>
                                {{$item->snippet->description}}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{$item->snippet->publishedAt}}
                            </p>

                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>