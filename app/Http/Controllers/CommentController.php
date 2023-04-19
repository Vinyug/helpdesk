<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;


class CommentController extends Controller
{
    /**
     * Variable.
     *
     */
    protected $ticket_number_separate = '/';
    protected $thumbnail_width = 100;
    protected $thumbnail_height = 100;
    protected $resolved = 'Résolu';


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //      $this->middleware('permission:comment-list|comment-create|comment-edit|comment-delete', ['only' => ['index','show']]);
    //      $this->middleware('permission:comment-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:comment-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:comment-delete', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment, Ticket $ticket)
    {
        if(auth()->user()->id === $comment->user_id && $ticket->state !== $this->resolved) {
            $comment->delete();
            return redirect()->back()->with('success','Le commentaire a été supprimé avec succès.');
        }

        return redirect()->back()->with('status','Vous n\'avez pas l\'autorisation de supprimer ce commentaire.');
    }
}
