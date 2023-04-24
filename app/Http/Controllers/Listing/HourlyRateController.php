<?php

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class HourlyRateController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:times-list|hourly_rate-create', ['only' => ['index','show']]);
         $this->middleware('permission:hourly_rate-create', ['only' => ['create','store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get last hourly_rate in listings
        $hourlyRate = Listing::whereNotNull('hourly_rate')
            ->where('hourly_rate', '!=', '')
            ->pluck('hourly_rate')
            ->last();

        return view('times.index', compact('hourlyRate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'hourly_rate' => 'sometimes|nullable|numeric|regex:/^\d{1,2}(\.\d{1,2})?$/', //number 00.00
        ], [
            'hourly_rate.regex' => 'Le champ taux horaire doit être un nombre respectant dans sa valeur maximum cette syntaxe 00.00'
        ]);
    
        Listing::create(['hourly_rate' => $request->input('hourly_rate')]);
    
        return redirect()->route('hourly_rate.index')
                        ->with('success', 'Un nouveau taux horaire est crée.');
    }
}
