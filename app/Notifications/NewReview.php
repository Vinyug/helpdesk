<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReview extends Notification
{
    use Queueable;

    protected Review $review;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
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
                        ->subject('Nouvel avis sur '. config('app.name').'!')
                        ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                        ->line('
                            Un nouvel avis a été rédigé par '.$this->review->user->firstname.' '.$this->review->user->lastname.
                            ' de l\'entreprise '.$this->review->user->company->name.'.
                            ')
                        ->line('Vous pouvez le consulter en cliquant sur le bouton ci-dessous.')
                        ->action('Voir l\'avis', route('reviews.edit', $this->review->id))
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
