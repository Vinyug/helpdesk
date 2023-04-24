<?php

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class StateController extends Controller
{
    protected $notSeen = 'Non lu';
    protected $seen = 'Lu';
    protected $resolved = 'Résolu';

    function __construct()
    {
         $this->middleware('permission:state-list|state-create|state-edit|state-delete', ['only' => ['index','show']]);
         $this->middleware('permission:state-create', ['only' => ['create','store']]);
         $this->middleware('permission:state-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:state-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $listings = Listing::select('id', 'state')->whereNotNull('state')->where('state', '!=', '')->distinct();

        return view('states.index', compact('listings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $state = Listing::get();

        return view('states.create', compact('state'));
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
            'state' => 'required|max:50|unique:listings,state',
        ]);
    
        Listing::create(['state' => $request->input('state')]);
    
        return redirect()->route('states.index')
                        ->with('success', 'Un nouvel état est crée.');
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
        $states = Listing::where('state', '!=', '')->whereNotNull('state')->pluck('state');
        
        if ($states->contains($listing->state)) {
            return view('states.edit', compact('listing'));
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
            'state' => 'required|max:50|unique:listings,state',
        ]);
    
        $listing = Listing::findOrFail($id);
        if ($listing->state !== $this->seen && $listing->state !== $this->notSeen && $listing->state !== $this->resolved) {
            $listing->state = $request->input('state');
            $listing->save();

            return redirect()->route('states.index')
                            ->with('success', 'L\'état est mis à jour.');
        }
        return redirect()->back()->with('status', 'Cet état ne peut pas être modifié.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);

        if ($listing->state !== $this->seen && $listing->state !== $this->notSeen && $listing->state !== $this->resolved) {
            $listing->delete();

            return redirect()->route('states.index')
                            ->with('success', 'L\'état est supprimé');
        }
        return redirect()->back()->with('status', 'Cet état ne peut pas être modifié.');
    }
}
