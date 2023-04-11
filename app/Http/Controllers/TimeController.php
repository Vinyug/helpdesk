<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
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
            'time_spent' => 'sometimes|integer',
        ]);

  
        // ---------------------------------------------------------------
        // --------------------------- VARIABLE --------------------------
        // ---------------------------------------------------------------
       
        
        
        // ---------------------------------------------------------------
        // --------------------------- INSERT ----------------------------
        // ---------------------------------------------------------------
        
        


        // ---------------------------------------------------------------
        // ---------------------------- VIEW -----------------------------
        // ---------------------------------------------------------------

        return redirect()->back()->with('success','Les informations ont été enregistrées avec succès.');
            
    }
}
