<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReturnAcceptNotification extends Notification
{
    use Queueable;
    public $borrowRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct($borrowRequest)
    {
        $this->borrowRequest = $borrowRequest;
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

   
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Return Book Notification',
            'message' => 'Hello, '.$this->borrowRequest->user->name.' Your book '.$this->borrowRequest->book->title.' has been returned to the '.$this->borrowRequest->library->name.'. We hope you enjoyed the book. Thanks...',
            'time' => now()->format('M d, Y g:i A'),
        ];
    }
}
