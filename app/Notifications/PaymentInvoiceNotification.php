<?php

namespace App\Notifications;

use App\Http\Controllers\Member\InvoiceGeneratorController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PaymentInvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $payment;
    protected $invoiceGenerator;
    public function __construct($payment)
    {
        //
        $this->payment = $payment;
        $this->invoiceGenerator = new InvoiceGeneratorController();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $invoice = $this->invoiceGenerator->generateInvoice($this->payment);

        if (!$invoice) {
            Log::error('Invoice generation returned null, sending mail without attachment.');
            return (new MailMessage)
                ->view('mail.payment_invoice', [])
                ->subject('Your Payment Invoice');
        }

        $invoice_number = $invoice['invoice_number'];
        $amount = $invoice['amount'];
        $receipt_email = $invoice['receipt_email'];
        $date = $invoice['date'];

        return (new MailMessage)
            ->view('mail.payment_invoice', compact('amount','invoice_number','receipt_email','date'))
            ->subject('Your Payment Invoice')
            ->attachData(
                $invoice['pdf']->output(),
                $invoice['filename'],
                ['mime' => 'application/pdf']
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
