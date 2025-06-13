<x-guest-layout>
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Left column - Image and action buttons -->
        <div class="flex flex-col">
            <div class="rounded-t-xl overflow-hidden shadow-md h-80">
                <img src="{{asset('storage/'.$restaurant->image_path)}}" alt="{{$restaurant->name}}" class="w-full h-full object-cover">
            </div>
            <div class="flex shadow-md">
                <a href="{{asset('storage/'.$restaurant->menu_path)}}" target="_blank" class="w-full py-4 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-bl-xl bg-white text-gray-800 hover:bg-gray-50 transition-all focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                    <img src="{{asset('icons/book-open-text.svg')}}" alt="" class="w-5 h-5">
                    {{__('messages.open_menu')}}
                </a>
                <button onclick="openMap({{$restaurant->latitude}}, {{$restaurant->longitude}})" class="w-full py-4 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-br-xl bg-white text-gray-800 hover:bg-gray-50 transition-all focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                    <img src="{{asset('icons/map.svg')}}" alt="" class="w-5 h-5">
                    {{__('messages.open_on_map')}}
                </button>
            </div>
        </div>

        <!-- Right column - Restaurant details -->
        <div class="flex flex-col space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-4">{{__('messages.description')}}</h3>
                <p class="text-gray-700 leading-relaxed">{{$restaurant->description}}</p>
            </div>

            @if($restaurant->tags->isNotEmpty())
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-semibold mb-4">{{__('messages.key_words')}}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($restaurant->tags as $index => $tag)
                            <span class="inline-block text-gray-800 text-xs font-medium px-3 py-1.5 rounded-full" style="background-color: {{$tag->color}};">
                               # {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('scripts/openMap')
</x-guest-layout>
