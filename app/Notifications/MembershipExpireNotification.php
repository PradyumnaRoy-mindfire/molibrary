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
        $time = now();

        if ($time->isToday()) {
            $formattedTime = 'Today, ' . $time->format('g A');
        } elseif ($time->isYesterday()) {
            $formattedTime = 'Yesterday, ' . $time->format('g A');
        } else {
            $formattedTime = $time->format('M d, Y g:i A');  // fallback format
        }
        return [
            'title' => 'Membership Expiry Reminder',
            'message' => 'Your ' . $this->membership->plan->type . ' membership will expire on ' . $this->membership->end_date,
            'action_url' =>  "http://molibrary.in/memberships/checkout/".$this->membership->plan->id,
            'action_text' => 'Renew Membership',
            'time' => $formattedTime,
        ];
    }
}
