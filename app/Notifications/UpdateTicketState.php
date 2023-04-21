<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateTicketState extends Notification
{
    use Queueable;

    protected Ticket $ticket;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(config('app.name').' - Ticket N° : '.$this->ticket->ticket_number.' / Entreprise - '.$this->ticket->company->name)
                    ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                    ->line('Votre administrateur de l\'application '.config('app.name').' vous informe de l\'état d\'avancement du ticket N° '.$this->ticket->ticket_number.'.')
                    ->line('État d\'avancement : '.$this->ticket->state)
                    ->line('Vous pouvez suivre le ticket en cliquant sur le bouton ci-dessous.')
                    ->action('Voir le ticket', route('tickets.show', $this->ticket->uuid))
                    ->salutation('A bientôt sur '.config('app.name').'!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
