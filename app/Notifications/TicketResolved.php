<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketResolved extends Notification
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
        if ($notifiable->email === $this->ticket->user->email) {
            return (new MailMessage)
                        ->subject(config('app.name').' - Ticket N° : '.$this->ticket->ticket_number)
                        ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                        ->line('Votre administrateur sur '.config('app.name').' a cloturé le ticket n°'.$this->ticket->ticket_number.'.')
                        ->line('Nous vous remercions par avance de rédiger un avis en cliquant sur le bouton ci-dessous.')
                        ->action('Écrite un avis', route('reviews.create'))
                        ->salutation('A bientôt sur '.config('app.name').'!');
        } else {
            return (new MailMessage)
                        ->subject(config('app.name').' - Ticket N° : '.$this->ticket->ticket_number)
                        ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                        ->line('Votre administrateur sur '.config('app.name').' a cloturé le ticket n°'.$this->ticket->ticket_number.'.')
                        ->line('Vous pouvez toujours consulter ce ticket.')
                        ->action('Voir ticket', route('tickets.show', $this->ticket->uuid))
                        ->salutation('A bientôt sur '.config('app.name').'!');
        }
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
