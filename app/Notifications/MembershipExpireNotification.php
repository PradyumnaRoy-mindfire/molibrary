<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipExpireNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $membership;

    /**
     * Create a new notification instance.
     */
    public function __construct($membership)
    {
        $this->membership = $membership;
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
            'title' => 'Membership Expiry Reminder',
            'message' => 'Hello,'.auth()->user()->name.'Your ' . $this->membership->plan->type . ' membership will expire on ' . $this->membership->end_date.' Please renew the membership to get more features as soon as possible.Thanks...',
            'action_url' =>  "http://molibrary.in/memberships/checkout/".$this->membership->plan->id,
            'action_text' => 'Renew Membership',
            'time' => now()->format('M d, Y g:i A'),
        ];
    }
}
