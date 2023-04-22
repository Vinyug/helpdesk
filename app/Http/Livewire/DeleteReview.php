<?php

namespace App\Http\Livewire;

use App\Models\Review;
use LivewireUI\Modal\ModalComponent;

class DeleteReview extends ModalComponent
{
    public Review $review;

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
        if ($this->review) {
            Review::query()->find($this->review->id)->delete();
        }

        $this->closeModalWithEvents([
            'pg:eventRefresh-default',
        ]);
    }
    public function render()
    {
        return view('livewire.modal_powergrid.delete-review');
    }
}
