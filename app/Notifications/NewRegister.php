<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRegister extends Notification
{
    use Queueable;

    protected User $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
                    ->subject('Nouvel utilisateur sur '.config('app.name'))
                    ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                    ->line('Un nouvel utilisateur, '.$this->user->firstname.' '.$this->user->lastname.' s\'est enregistré sur '.config('app.name').'!')
                    ->line('
                        Veuillez prendre connaissance de sa fiche de profil en cliquant sur le bouton ci-dessous afin de lui attribuer une entreprise et lui permettre de créer un ticket.')
                    ->action('Voir profil', route('users.edit', $this->user->id))
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
