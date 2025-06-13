 <!-- Card -->
 <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-2xs rounded-xl">
     <a href="{{ route('restaurants.show', $id) }}">
         <div class="h-52 bg-amber-500 rounded-t-xl overflow-hidden group">
             <img
                 src="{{ asset('storage/' . $imagePath) }}"
                 alt=""
                 class="h-52 w-full object-cover transform transition-transform duration-300 group-hover:scale-110"
             >
         </div>
     </a>
     <div class="p-4 md:p-6">
         <h3 class="text-xl font-semibold text-gray-800">
             {!! $name !!}
         </h3>
             <p class="my-2 text-gray-500">
                 {!! \Illuminate\Support\Str::words($description, 30, '...') !!}
             </p>
             <div class="flex flex-wrap gap-x-1">
                 @foreach ($tags as $index => $tag)
                     <span class="inline-block text-gray-800 text-xs font-medium px-2 py-1 rounded-full" style="background-color: {{$tag->color}};">
                        #{{ $tag->name }}
                    </span>
                 @endforeach
             </div>
     </div>
     <div class="mt-auto flex border-t border-gray-200 divide-x divide-gray-200">
         <a href="{{asset('storage/'.$menuPath)}}" target="_blank" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-es-xl bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
             <img src="{{asset('icons/book-open-text.svg')}}" alt="" class="w-5 h-5">
             {{__('messages.open_menu')}}
         </a>
         <button onclick="openMap({{$latitude}}, {{$longitude}})" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-ee-xl bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
             <img src="{{asset('icons/map.svg')}}" alt="" class="w-5 h-5"> {{__('messages.open_on_map')}}
         </button>
     </div>
 </div>
 <!-- End Card -->
