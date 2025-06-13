<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $restaurants = Restaurant::search($search)->get();

        return view('restaurants.index', [
            'restaurants' => $restaurants,
            'search' => $search,
        ]);
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->load('tags');

        return view('restaurants.show', compact('restaurant'));
    }
}
