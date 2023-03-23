<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Listing;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:ticket-list|ticket-create|ticket-edit|ticket-delete', ['only' => ['index','show']]);
         $this->middleware('permission:ticket-create', ['only' => ['create','store']]);
         $this->middleware('permission:ticket-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:ticket-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // query in powergrid table : TicketTable
        return view('tickets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $services = Listing::whereNotNull('service')->where('service','!=', '')->pluck('service', 'service');

        return view('tickets.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // data validation
        $request->validate([
            'subject' => 'required|max:80',
            'service' => 'required|exists:listings,service',
            'content' => 'required',
        ]);

        // dd($request);
        // dd(Auth::user()->id);

        // user_id
        $user_id = Auth::user()->id;
        // company_id
        $company_id = Auth::user()->company_id;
        // generate ticket number
        $ticket_number = $this->generateTicketNumber();
        // genererate uuid
        $uuid = Str::uuid()->toString();

        //------ INSERT --------
        // DB tickets
        $ticket = Ticket::create(array_merge([
            'subject' => $request['subject'],
            'service' => $request['service'],
        ], 
        compact('user_id', 'company_id', 'ticket_number', 'uuid')));

        // DB comments
        // ticket_id
        $ticket_id = $ticket->id;

        // insert
        $comment = Comment::create(array_merge([
            'content' => $request['content'],
        ], 
        compact('user_id', 'ticket_id')));

        // redirect with message
        return redirect()->route('tickets.show', $uuid)->with('success','Le ticket a été enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        // get all comments of ticket
        $comments = Comment::where('ticket_id', '=', $ticket->id)->latest()->get();
        
        return view('tickets.show',compact('ticket', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        $services = Listing::whereNotNull('service')->where('service','!=', '')->pluck('service', 'service');
        $comment = Comment::where('ticket_id', '=', $ticket->id)->first();
        return view('tickets.edit',compact('ticket', 'services', 'comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket, Comment $comment)
    {
        $request->validate([
            'subject' => 'required|max:80',
            'service' => 'required|exists:listings,service',
            'content' => 'required',
        ]);
        
        // $ticket->fill($request->post())->save();
        
        //------ UPDATE --------
        // DB tickets
        $ticket->fill([
            'subject' => $request['subject'],
            'service' => $request['service'],
        ]);
        $ticket->save();
        
        // DB comments
        $comment->fill([
            'content' => $request['content'],
        ]);
        $comment->save();

        return redirect()->route('tickets.index')->with('success','Le ticket a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success','Le ticket a été supprimé avec succès');
    }


    /**
     * Custom method
     *
     */
    public function generateTicketNumber()
    {
        // user nesbot/carbon to know date of day
        $date = Carbon::now();
        // format into ddmmyy
        $dateFormat = $date->format('dmy');
        // count the number of tickets of the day
        $dayTicket = Ticket::whereDate('created_at', $date)->count();
        // and increment it
        $dayTicket++;
        // format ticket number on format - #ddmmyy/i
        $ticket_number = "#{$dateFormat}/{$dayTicket}";
        
        return $ticket_number;
    }
}
