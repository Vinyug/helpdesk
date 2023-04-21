<?php

namespace App\Http\Controllers;

use App\Models\Listing;
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

        return view('index', compact('services'));
    }
}
