<?php

namespace App\Http\Livewire;

use App\Models\Listing;
use LivewireUI\Modal\ModalComponent;

class DeleteService extends ModalComponent
{
    public Listing $listing;

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
        if ($this->listing) {
            Listing::query()->find($this->listing->id)->delete();
        }

        $this->closeModalWithEvents([
            'pg:eventRefresh-default',
        ]);
    }
    public function render()
    {
        return view('livewire.modal_powergrid.delete-service');
    }
}
