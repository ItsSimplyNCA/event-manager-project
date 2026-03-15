<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
# use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification {

    use Queueable;

    public function __construct(private string $token) {
        //
    }

    public function via(object $notifiable): array {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage {
        $url = rtrim(config('app.frontend_url'), '/')
            . '/reset-password?token=' . $this->token
            . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Password Reset')
            ->line('We have received a request to reset your password.')
            ->action('Reset your password', $url)
            ->line('If you did not make the request, just ignore this message.');
    }

    public function toArray(object $notifiable): array {
        return [
            //
        ];
    }
}
