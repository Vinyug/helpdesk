<?php

namespace App\Http\Livewire;

use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Role;

class DeleteRole extends ModalComponent
{
    public Role $role;

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
        if ($this->role) {
            Role::query()->find($this->role->id)->delete();
        }

        $this->closeModalWithEvents([
            'pg:eventRefresh-default',
        ]);
    }
    public function render()
    {
        return view('livewire.modal_powergrid.delete-role');
    }
}
