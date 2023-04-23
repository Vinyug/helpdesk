<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewContact extends Notification
{
    public $fullname;
    public $email;
    public $address;
    public $company;
    public $content;

    public function __construct($fullname, $email, $address, $company, $content)
    {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->address = $address;
        $this->company = $company;
        $this->content = $content;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau message de contact sur '.config('app.name'))
            ->greeting('Bonjour,')
            ->line('Vous avez reçu un nouveau message de contact :')
            ->line('Nom complet : '.$this->fullname)
            ->line('Email : '.$this->email)
            ->line('Entreprise : '.$this->company)
            ->line('Adresse : '.$this->address)
            ->line('Message : ')
            ->line($this->content)
            ->salutation('A bientôt sur '.config('app.name').'!');
    }
}
