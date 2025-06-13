<x-guest-layout>
    <!-- SearchBox -->
    <form action="{{route('restaurants.index')}}" method="GET" class="w-full mx-auto mb-6">
        <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input
                type="search"
                id="search"
                name="search"
                value="{{ $search }}"
                class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                placeholder="{!! __('messages.search.placeholder') !!}"
                data-small-placeholder="{!! \Illuminate\Support\Str::words(__('messages.search.placeholder'), 4, '...') !!}"
            />
            <button type="submit" class="text-white absolute px-4 py-2 end-2.5 bottom-2 bg-blue-500 hover:bg-blue-600 transition-all inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent focus:outline-none disabled:opacity-50 disabled:pointer-events-none cursor-pointer">{{__('messages.search.title')}}</button>
        </div>
    </form>
    <!-- End SearchBox -->

    <!-- Restaurants List -->
    @if($restaurants->isEmpty())
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.restaurants.empty.title') }}</h2>
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($restaurants as $restaurant)
                <x-restaurant-card
                    :id="$restaurant->id"
                    name="{{$restaurant->name}}"
                    description="{{$restaurant->description}}"
                    imagePath="{{$restaurant->image_path}}"
                    menuPath="{{$restaurant->menu_path}}"
                    longitude="{{$restaurant->longitude}}"
                    latitude="{{$restaurant->latitude}}"
                    :tags="$restaurant->tags"/>
            @endforeach
        </div>
    @endif

    @include('scripts/openMap')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('search');
            const fullPlaceholder = input.getAttribute('placeholder');
            const shortPlaceholder = input.getAttribute('data-small-placeholder');

            function updatePlaceholder() {
                input.setAttribute('placeholder', window.innerWidth < 640 ? shortPlaceholder : fullPlaceholder);
            }

            updatePlaceholder();
            window.addEventListener('resize', updatePlaceholder);
        });
    </script>
</x-guest-layout>
