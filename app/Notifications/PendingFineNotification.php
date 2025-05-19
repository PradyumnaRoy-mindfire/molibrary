<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendingFineNotification extends Notification
{
    use Queueable;
    public $fine;
    /**
     * Create a new notification instance.
     */
    public function __construct($fine)
    {
        $this->fine = $fine;
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
            'title' => 'Due Fine Reminder Notification',
            'message' => 'Hello, '.$this->fine->borrow->user->name.' You have a pending fine of Rs.'.$this->fine->amount.' for the book '.$this->fine->borrow->book->title.'. and the due date was '.$this->fine->borrow->due_date.'. Please settle the fine as soon as possible to maintain the borrow privileges.Thanks...',
            'action_url' =>  route('pay.fine', $this->fine->borrow->id),
            'action_text' => 'Pay Now',
            'time' => now()->format('M d, Y g:i A'),
        ];
    }
}
