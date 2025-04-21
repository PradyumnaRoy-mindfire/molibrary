<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;

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
            'description' => $plan->type . " Plan",
            'source' => $request->stripeToken,
            'receipt_email' => $request->email,
        ]);

        // saving to transactions table
        Transaction::create([
            'user_id' => $user->id ,
            'amount' => $amount ,
            'payment_method_id' => $charge->payment_method ,
            'status' => $charge->status ,
            'type' => 'membership' ,
            'method' => 'card'
        ]);

        // create record to membership table for the user 
        Membership::create([
            'payments_method_id' => $charge->payment_method,
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'has_access' => $charge->status === 'succeeded' ? 1 : 0 ,
            'start_date' => now(),
            'end_date' => now()->addMinutes($plan->duration),
            'renewed_at' => now(),
        ]);

        if ($charge->status === 'failed') {
            return redirect()->back()->with('payment_failed', 'Payment failed. Please try again.');
        }
        return redirect()->back()->with('payment_success', 'Payment successful!');
    }
}
