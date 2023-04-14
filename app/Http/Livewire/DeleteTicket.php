<?php

namespace App\Http\Livewire;

use App\Models\Ticket;
use LivewireUI\Modal\ModalComponent;

class DeleteTicket extends ModalComponent
{
    public Ticket $ticket;

    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public static function closeModalOnEscape(): bool
    {
        return true;
    }

    public static function closeModalOnClickAway(): bool
    {
        return true;
    }

    public function cancel()
    {
        $this->closeModal();
    }

    public function confirm()
    {
        if ($this->ticket) {
            Ticket::query()->find($this->ticket->uuid)->delete();
        }

        $this->closeModalWithEvents([
            'pg:eventRefresh-default',
        ]);
    }
    public function render()
    {
        return view('livewire.modal_powergrid.delete-ticket');
    }
}