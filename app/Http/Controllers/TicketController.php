<?php

namespace App\Http\Controllers;

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
            'service' => 'required|exists:listings,service',
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

        // insert in DB
        Ticket::create(array_merge($request->post(), compact('user_id', 'company_id', 'ticket_number', 'uuid')));

        // redirect with message
        return redirect()->route('tickets.index')->with('success','Le ticket a été enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show',compact('ticket'));
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

        return view('tickets.edit',compact('ticket', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'service' => 'required|exists:listings,service',
        ]);
        
        $ticket->fill($request->post())->save();

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
