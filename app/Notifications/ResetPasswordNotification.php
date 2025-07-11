<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;
    public $email;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email; // Ajout de l'email
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via()
    {
        return ['mail']; // Indique que la notification sera envoyée par email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
{
    // Lien sans token ni email
    $frontendUrl = config('app.frontend_url') . '/auth/reset-password?token=' . $this->token . '&email=' . urlencode($this->email);

    return (new MailMessage)
        ->subject('Reset Your Password')
        ->line('You are receiving this email because we received a password reset request for your account.')
        ->action('Reset Password', $frontendUrl)
        ->line('If you did not request a password reset, no further action is required.');
    }

}
