<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Notifications\NewContact;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ContactForm extends Component
{
    public $fullname;
    public $email;
    public $address;
    public $company;
    public $content;

    protected $rules = [
        'fullname' => 'required|string',
        'email' => 'required|email',
        'address' => 'nullable|string',
        'company' => 'nullable|string',
        'content' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.contact-form');
    }

    public function submit()
    {
        $validatedData = $this->validate();
        
        $admin = User::permission('all-access')->get();
       
        if(env('MAIL_USERNAME')) {
            Notification::send($admin, new NewContact(
                $validatedData['fullname'],
                $validatedData['email'],
                $validatedData['address'],
                $validatedData['company'],
                $validatedData['content']
            ));

            session()->flash('success', 'Votre message a bien été envoyé !');
        }

        $this->reset();
    }
}