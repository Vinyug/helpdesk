<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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
        $review = Review::create(array_merge(
            [
            'rate' => $request['rate'],
            'content' => $request['content'],
            'visibility' => $request['visibility'] ? 1 : 0,
            ],
            compact('user_id', 'show')
        ));
        
        // ------ NOTIFICATION ------
        // Notify super admin
        $admin = User::permission('all-access')->get();

        if (env('MAIL_USERNAME')) {
            Notification::send($admin, new NewReview($review));
        }

        // ------ VIEW ------
        return back()->with('status', 'Nous vous remercions d\'avoir rédigé un avis!');
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
    public function edit(Review $review, User $user)
    {
        if (Auth()->user()->can('review-list')) {
            return view('reviews.edit', compact('review', 'user'));
        }
        
        return redirect()->route('index');
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
        if (Auth()->user()->can('review-list')) {
            // ----- DATA VALIDATION -----
            $request->validate([
                'show' => 'boolean',
            ]);

            // ----- UPDATE -----
            $review = Review::find($id);
            $review->show = $request->input('show') ? 1 : 0;
            $review->save();
       
            return redirect()->route('reviews.index')->with('success', 'L\'avis a été mis à jour avec succès.');
        }
        
        return redirect()->route('index');
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
                        ->with('success', 'L\'avis est supprimé');
    }
}
