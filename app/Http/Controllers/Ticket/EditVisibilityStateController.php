<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class EditVisibilityStateController extends Controller
{
    public function update(Request $request, Ticket $ticket)
    {
        if(auth()->user()->can('all-access')) {
            $request->validate([
                'visibility' => 'boolean',
                'state' => 'required|exists:listings,state|not_in:null,',
            ]);

            if($request['state'] !== 'Non lu') {
                $ticket->editable = 0;
            }

            //------ UPDATE --------
            $ticket->fill([
                'visibility' => $request['visibility'] ? 0 : 1,
                'state' => $request['state'],
                'editable' => $ticket->editable,
            ]);
            $ticket->update();

            return redirect()->back()->with('success','Le ticket a été mis à jour avec succès.');
        }

        return redirect()->route('tickets.index')->with('status','Vous n\'avez pas l\'autorisation de modifier ce ticket.');
    }
}
