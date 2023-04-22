<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:review-list|role-edit|role-delete', ['only' => ['index']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // query in powergrid table : ReviewTable
        return view('reviews.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // ----- DATA VALIDATION ----- 
        $request->validate([
            'rate' => 'required|integer|between:1,5',
            'content' => 'nullable',
            'visibility' => 'boolean',
        ]);


        // ----- VARIABLE -----
        // user_id
        $user_id = auth()->user()->id;
        // show
        $show = 0;


        // ------ INSERT ------
        $review = Review::create(array_merge([
            'rate' => $request['rate'],
            'content' => $request['content'],
            'visibility' => $request['visibility'] ? 0 : 1,
        ], 
        compact('user_id', 'show')));
        

        // ------ VIEW ------
        return back()->with('status','Nous vous remercions d\'avoir rédigé un avis!');

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return redirect()->route('reviews.index')
                        ->with('success','L\'avis est supprimé');
    }
}
