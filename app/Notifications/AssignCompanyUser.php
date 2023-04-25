<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignCompanyUser extends Notification
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
                    ->subject(config('app.name').' - Affection d\'entreprise : '.$this->user->company->name)
                    ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                    ->line('Une nouvelle affection d\'entreprise a été enregistrée par un administrateur sur '.config('app.name').'!')
                    ->line('L\'utilisateur '.$this->user->firstname.' '.$this->user->lastname.' est affecté à l\'entreprise '.$this->user->company->name)
                    ->line('Pour voir la liste des utilisateurs de la société, cliquez ci-dessous.')
                    ->action('Se connecter', route('users.index'))
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
