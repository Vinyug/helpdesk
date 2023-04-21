<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\UpdateTicketState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class EditVisibilityStateController extends Controller
{
    public function update(Request $request, Ticket $ticket)
    {
        $ticketCurrentState = $ticket->state;

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

            // ---------------- Notification ---------------------
            if($request['state'] !== $ticketCurrentState) {
                // Notify user and admin company of company
                $adminCompany = User::permission('ticket-private')
                ->where('company_id','=', $ticket->user->company_id)
                ->get(); 
                // merge to send
                $userAndAdminCompany = collect([$ticket->user])->merge($adminCompany)->unique('id');

                Notification::send($userAndAdminCompany, new UpdateTicketState($ticket));
            }

            return redirect()->back()->with('success','Le ticket a été mis à jour avec succès.');
        }

        return redirect()->route('tickets.index')->with('status','Vous n\'avez pas l\'autorisation de modifier ce ticket.');
    }
}
