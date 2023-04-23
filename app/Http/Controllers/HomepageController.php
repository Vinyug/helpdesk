<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Review;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $services = Listing::where('service', '!=', '')->whereNotNull('service')->pluck('description', 'service');
        $reviews = Review::where('show', '1')->get();

        return view('index', compact('services', 'reviews'));
    }
}
