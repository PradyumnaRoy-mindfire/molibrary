<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\Membership;
use App\Models\Plan;
use App\Models\Transaction;
use App\Notifications\PaymentInvoiceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentMethod;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Log;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    //

    public function showCheckoutForm(Plan $plan)
    {
        return view('payment.checkout', compact('plan'));
    }

    public function processCheckout(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $amount = $request->amount;

        Stripe::setApiKey(config('services.stripe.secret'));

        $charge = Charge::create([
            'amount' => $amount * 100,
            'currency' => 'inr',
            'description' => "You have purchased " . $plan->type . " Membership Plan",
            'source' => $request->stripeToken,
            'receipt_email' => $request->email,
            'metadata' => [
                'name' => $request->name,
            ],
        ]);

        // saving to transactions table
        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'payment_method_id' => $charge->payment_method,
            'status' => $charge->status,
            'type' => 'membership',
            'method' => 'card'
        ]);

        // creating record to membership table for the user 
        $membership = Membership::create([
            'payments_method_id' => $charge->payment_method,
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'has_access' => $charge->status === 'succeeded' ? 1 : 0,
            'start_date' => now(),
            'end_date' => now()->addMinutes($plan->duration),
            'renewed_at' => now(),
        ]);
        $user->book_limit = $membership->plan->max_books_limit;
        $user->save();


        //send payment invoice notification
        $user->notify(new PaymentInvoiceNotification($charge));

        if ($charge->status === 'failed') {
            return redirect()->back()->with('payment_failed', 'Payment failed. Please try again.');
        }
        return redirect()->back()->with('payment_success', 'Payment successful!');
    }

    public function payFine(Borrow $borrow)
    {
        $amount = $borrow->fine->amount;
        $book = $borrow->book->title;
        $library = $borrow->library->name;

        return view('payment.fine_checkout', compact('amount', 'book', 'library', 'borrow'));
    }

    public function processFine(Request $request, Borrow $borrow)
    {
        $amount = $request->amount;
        $user = Auth::user();
        Stripe::setApiKey(config('services.stripe.secret'));

        $charge = Charge::create([
            'amount' => $amount * 100,
            'currency' => 'inr',
            'description' => "Fine for " . $borrow->book->title . " overdue at " . $borrow->library->name,
            'source' => $request->stripeToken,
            'receipt_email' => $request->email,
            'metadata' => [
                'name' => $request->name,
            ],
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'payment_method_id' => $charge->payment_method,
            'status' => $charge->status,
            'type' => 'fine',
            'method' => 'card'
        ]);
        Fine::where('borrow_id', $borrow->id)->update([
            'status' => $charge->status === 'succeeded' ? 'paid' : 'pending',
            'payment_id' => $charge->payment_method
        ]);

        //send payment invoice notification
        $payment = $charge;
        auth()->user()->notify(new PaymentInvoiceNotification($payment));

        if ($charge->status === 'failed') {
            return redirect()->back()->with('payment_failed', 'Payment failed. Please try again.');
        }


        return redirect()->back()->with('payment_success', 'Payment successful!');
    }
}
