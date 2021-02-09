<ul class="my-3">
    @foreach($searchResponse as $item)
        <li class="list-none grid grid-cols-6 p-3 gap-3 bg-white mb-3 rounded border-gray-500 shadow">
            <div class="col-span-1" x-data="{open : false}">
                <img src="{{ $item->snippet->thumbnails->high->url }}" alt="" class="w-100" @click="open = true">
                <div x-show="open" @click.away="open = false">
                    <a href="#" @click.prevent="open = false">닫기</a>
                    <iframe src="https://youtube.com/embed/{{ $item->id->videoId }}" frameborder="0" width="100%"></iframe>
                </div>
            </div>
            <div class="col-span-5">
                <a href="https://youtube.com/watch?v={{ $item->id->videoId }}" target="_blank">{{ $item->snippet->title }}</a>
                <hr class="my-2">
                <p class="text-sm">
                    {{$item->snippet->description}}
                </p>
                <p class="text-sm text-gray-500">
                    {{$item->snippet->publishedAt}}
                </p>
                <hr>
                <div class="mt-2" x-data="check()" @if($loop->first) x-init="ck = true" @endif>
                    <label for="video_{{ $item->id->videoId }}">
                        <template x-if="ck == true">
                            <span>사용</span>
                        </template>
                        <template x-if="ck == false">
                            <span>미사용</span>
                        </template>
                    </label>
                    <input type="checkbox" name="videoId[]" value="{{ $item->id->videoId }}" id="video_{{ $item->id->videoId }}" @click="toggle()" :checked="ck">
                </div>
            </div>
        </li>
    @endforeach
    <script>
        function check(){
            return{
                ck : false,
                toggle(){
                    this.ck = !this.ck;
                }
            }
        }
    </script>
</ul>