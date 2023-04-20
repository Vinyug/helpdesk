<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicket extends Notification
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
                    ->subject('Nouveau Ticket: Entreprise - '.$this->ticket->company->name.' / N° Ticket - '.$this->ticket->ticket_number)
                    ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                    ->line('
                        Un nouveau ticket N° '.$this->ticket->ticket_number.
                        ' à été ouvert par l\'entreprise '.$this->ticket->company->name.
                        ' et rédigé par '.$this->ticket->user->firstname.' '.$this->ticket->user->lastname.'.
                        ')
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
