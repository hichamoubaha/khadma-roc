<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class CandidatureNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $statut;

    public function __construct($message, $statut)
    {
        $this->message = $message;
        $this->statut = $statut;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Bonjour ' . $notifiable->name)
            ->line($this->message)
            ->action('Voir la candidature', url('/'))
            ->line('Merci d\'utiliser notre plateforme !');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'statut' => $this->statut,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'statut' => $this->statut,
        ]);
    }
}
