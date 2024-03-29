<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketResolved;
use App\Notifications\UpdateTicketState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class EditVisibilityStateController extends Controller
{
    protected $resolved = 'Résolu';

    public function update(Request $request, Ticket $ticket)
    {
        $ticketCurrentState = $ticket->state;

        if (auth()->user()->can('all-access')) {
            $request->validate([
                'visibility' => 'boolean',
                'state' => 'required|exists:listings,state|not_in:null,',
            ]);

            if ($request['state'] !== 'Non lu') {
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
            $listOfUsersNotifiable = $this->listOfUsersNotifiable($ticket);
            
            if (env('MAIL_USERNAME')) {
                // if ticket state is resolved => notify
                ($ticket->state === $this->resolved) &&
                    (Notification::send($listOfUsersNotifiable, new TicketResolved($ticket)));
                
                // if ticket state change and not resolved => notify
                ($request['state'] !== $ticketCurrentState && $ticket->state !== $this->resolved) &&
                    (Notification::send($listOfUsersNotifiable, new UpdateTicketState($ticket)));
            }
            

            return redirect()->back()->with('success', 'Le ticket a été mis à jour avec succès.');
        }

        return redirect()->route('tickets.index')->with('status', 'Vous n\'avez pas l\'autorisation de modifier ce ticket.');
    }

    public function listOfUsersNotifiable(Ticket $ticket)
    {
        // Notify user and admin company of company
        $adminCompany = User::permission('ticket-private')
        ->where('company_id', '=', $ticket->company->id)
        ->get();
        // merge to send
        $userAndAdminCompany = collect([$ticket->user])->merge($adminCompany)->unique('id');

        return $userAndAdminCompany;
    }
}
