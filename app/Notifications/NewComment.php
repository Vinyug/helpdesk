<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    protected Comment $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
                        ->subject(config('app.name').' Nouveau commentaire - N° Ticket - '.$this->comment->ticket->ticket_number.' / Entreprise : '.$this->comment->ticket->company->name)
                        ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                        ->line('
                            Un nouveau commentaire a été rédigé sur le ticket N° '.$this->comment->ticket->ticket_number.
                            ', par '.$this->comment->user->firstname.' '.$this->comment->user->lastname.
                            ' de l\'entreprise '.$this->comment->ticket->company->name.'.
                            ')
                        ->line('Vous pouvez consulter le ticket en cliquant sur le bouton ci-dessous')
                        ->action('Voir le ticket', route('tickets.show', $this->comment->ticket->uuid))
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
