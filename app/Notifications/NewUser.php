<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUser extends Notification
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
        if($notifiable->email === $this->user->email) {
            return (new MailMessage)
                        ->subject('Nouvel utilisateur sur '.config('app.name').' - Entreprise : '.$this->user->company->name)
                        ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                        ->line('Vous avez été enregistré par un administrateur sur '.config('app.name').'!')
                        ->line('Vous êtes affecté à l\'entreprise '.$this->user->company->name.'.')
                        ->line('')
                        ->line('Pour vous connecter, merci de bien vouloir cliquer sur le lien ci-dessous et créer un mot de passe.')
                        ->line('Votre identifiant est : '.$this->user->email)
                        ->action('Nouveau mot de passe', route('password.email'))
                        ->salutation('A bientôt sur '.config('app.name').'!');
        } else {
            return (new MailMessage)
                        ->subject('Nouvel utilisateur sur '.config('app.name').' - Entreprise : '.$this->user->company->name)
                        ->greeting('Bonjour '.$notifiable->firstname.' '.$notifiable->lastname.',')
                        ->line('Un nouvel utilisateur, '.$this->user->firstname.' '.$this->user->lastname.' a été enregistré par un administrateur sur '.config('app.name').'!')
                        ->line('Ce nouveau membre est affecté à l\'entreprise '.$this->user->company->name.'.')
                        ->line('')
                        ->line('Vous pouvez accéder à la liste des utilisateurs en cliquant ci-dessous.')
                        ->action('Voir liste des utilisateurs', route('users.index'))
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
