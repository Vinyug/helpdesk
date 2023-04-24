<?php

namespace App\Http\Livewire;

use App\Models\Company;
use LivewireUI\Modal\ModalComponent;

class DeleteCompany extends ModalComponent
{
    public Company $company;

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
        if ($this->company) {
            Company::query()->find($this->company->uuid)->delete();
        }

        $this->closeModalWithEvents([
            'pg:eventRefresh-default',
        ]);
    }
    public function render()
    {
        return view('livewire.modal_powergrid.delete-company');
    }
}
