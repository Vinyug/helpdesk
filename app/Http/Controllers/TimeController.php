<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Ticket;
use App\Models\Time;
use Illuminate\Http\Request;

class TimeController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ticket $ticket)
    {
        // ---------------------------------------------------------------
        // ---------------------- DATA VALIDATION ------------------------
        // ---------------------------------------------------------------
        $request->validate([
            'visibility' => 'boolean',
            'state' => 'required|exists:listings,state|not_in:null,',
            'time_spent' => 'sometimes|nullable|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/', //number 0000,00
        ], [
            'time_spent.regex' => 'Le champ temps passé doit être un nombre respectant dans sa valeur maximum cette syntaxe 0000.00'
        ]);

  
        // ---------------------------------------------------------------
        // --------------------------- VARIABLE --------------------------
        // ---------------------------------------------------------------
        
        // get ticket id
        $ticket_id = $ticket->id; 
        // get hourly_rate
        $hourly_rate = Listing::whereNotNull('hourly_rate')
            ->where('hourly_rate','!=', '')
            ->pluck('hourly_rate')
            ->last();
        
    
        // ---------------------------------------------------------------
        // --------------------------- INSERT ----------------------------
        // ---------------------------------------------------------------
        
        // --------------------------- TIME ----------------------------
        $time = Time::create(array_merge([
            'time_spent' => $request['time_spent'],
        ], 
        compact('ticket_id', 'hourly_rate')));


        // ---------------------------------------------------------------
        // ---------------------------- VIEW -----------------------------
        // ---------------------------------------------------------------

        return redirect()->back()->with('success','Les informations ont été enregistrées avec succès.');
            
    }

    public function calculateTicketTotalTime($ticket_id)
    {
        $times = Time::where('ticket_id', $ticket_id)->get();
        $totalTime = 0;

        foreach($times as $time) {
            $time_spent = $time->time_spent;
            $totalTime += $time_spent;
        }

        return $totalTime;
    }
}
