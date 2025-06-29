<?php

namespace App\Notifications;

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminAlertNotification extends Notification
{
    public function __construct(
        public string $title,
        public string $message,
        public ?string $url = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->line($this->message);

        if ($this->url) {
            $mail->action('View Details', $this->url);
        }

        return $mail;
    }
}
