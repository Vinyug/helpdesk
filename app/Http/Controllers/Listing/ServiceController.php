<?php

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:service-list|service-create|service-edit|service-delete', ['only' => ['index','show']]);
         $this->middleware('permission:service-create', ['only' => ['create','store']]);
         $this->middleware('permission:service-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:service-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $listings = Listing::select('id', 'service')->whereNotNull('service')->where('service', '!=', '')->distinct();

        return view('services.index', compact('listings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service = Listing::get();

        return view('services.create',compact('service'));
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
            'service' => 'required|max:200',
        ]);
    
        Listing::create(['service' => $request->input('service')]);
    
        return redirect()->route('services.index')
                        ->with('success','Un nouveau service est crée.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $listing = Listing::findOrFail($id);
        $services = Listing::where('service', '!=', '')->whereNotNull('service')->pluck('service');
        
        if($services->contains($listing->service)) {
            return view('services.edit',compact('listing'));
        }

        return redirect()->back()->with('status', 'Vous n\'avez pas l\'autorisation d\'accéder à cette page.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'service' => 'required|max:200',
        ]);
    
        $listing = Listing::findOrFail($id);
        $listing->service = $request->input('service');
        $listing->save();
    
        return redirect()->route('services.index')
                        ->with('success','Le service est mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Listing::findOrFail($id)->delete();
        return redirect()->route('services.index')
                        ->with('success','Le service est supprimé');
    }
}