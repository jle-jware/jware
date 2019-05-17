<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyUsers extends Notification
{
    use Queueable;

    public $notificationInfo;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notificationInfo)
    {
        $this->notificationInfo = $notificationInfo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            'notify' => $this->notificationInfo
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'data' => [
                'notify' => $this->notificationInfo
            ]
        ];
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
