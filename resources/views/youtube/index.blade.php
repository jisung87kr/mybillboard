<x-app-layout>
    @include('layouts.slot_header')
    <div class="container max-w-7xl mx-auto p-4 sm:px-6 lg:px-8">
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
            <x-youtube-item-list :searchResponse="$searchResponse"/>
        @endif
    </div>
</x-app-layout>