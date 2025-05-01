<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //0 request new beneficiaries, 1 = transitioning, 2 = programs, 3 = birthday, 4 = aics, 5 = beneficiaries
            'type' => $this->details['type'],
            'from' => $this->details['from'],
            'image' => $this->details['image'],
            'message' => $this->details['message'],
            'url' => $this->details['url'], // Optional: Link to a specific page
            'created_at' => now(),
        ];
    }
}
