<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\Membership;
use App\Models\Plan;
use App\Models\Transaction;
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

        // creating record to membership table for the user 
        $membership = Membership::create([
            'payments_method_id' => $charge->payment_method,
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'has_access' => $charge->status === 'succeeded' ? 1 : 0 ,
            'start_date' => now(),
            'end_date' => now()->addMinutes($plan->duration),
            'renewed_at' => now(),
        ]);
        $user->book_limit = $membership->plan->max_books_limit;
        $user->save();

        if ($charge->status === 'failed') {
            return redirect()->back()->with('payment_failed', 'Payment failed. Please try again.');
        }
        return redirect()->back()->with('payment_success', 'Payment successful!');
    }

    public function payFine(Borrow $borrow){
        $amount = $borrow->fine->amount;
        $book = $borrow->book->title;
        $library = $borrow->library->name;
        
        return view('payment.fine_checkout', compact('amount','book','library','borrow'));
    }

    public function processFine(Request $request, Borrow $borrow){
        $amount = $request->amount;
        $user = Auth::user();
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $charge = Charge::create([
            'amount' => $amount * 100,
            'currency' => 'inr',
            'description' => "Fine for " . $borrow->book->title . " overdue at " . $borrow->library->name,
            'source' => $request->stripeToken,
            'receipt_email' => $request->email,
        ]);

        Transaction::create([
            'user_id' => $user->id ,
            'amount' => $amount ,
            'payment_method_id' => $charge->payment_method ,
            'status' => $charge->status ,
            'type' => 'fine' ,
            'method' => 'card'
        ]);
        Fine::where('borrow_id', $borrow->id)->update([
            'status' => $charge->status==='succeeded' ? 'paid' : 'pending',
            'payment_id' => $charge->payment_method
        ]);
        
        if ($charge->status === 'failed') {
            return redirect()->back()->with('payment_failed', 'Payment failed. Please try again.');
        }
       

        return redirect()->back()->with('payment_success', 'Payment successful!');
    }

    // public function showCheckoutForm(Plan $plan)
    // {
    //     return view('payment.checkout', compact('plan'));
    // }

    // public function processCheckout(Request $request, Plan $plan)
    // {
    //     // Get the payment method ID from the request
    //     $paymentMethodId = $request->input('payment_method_id');
    //     $name = $request->input('name');
    //     $email = $request->input('email');

    //     try {
    //         $paymentIntent = \Stripe\PaymentIntent::create([
    //             'amount' => $plan->amount, // Your amount in cents
    //             'currency' => 'usd',
    //             'payment_method' => $paymentMethodId,
    //             'confirmation_method' => 'manual',
    //             'confirm' => true,
    //             'description' => 'Your charge description',
    //             // 'customer' => auth()->user()->stripe_id
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'redirect_url' => route('checkout',$plan)
    //         ]);
    //     } catch (\Stripe\Exception\CardException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => $e->getMessage()
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => 'An error occurred while processing your payment.'
    //         ]);
    //     }
    // }


    // public function createCheckoutSession(Request $request, Plan $plan)
    // {
    //     try {
    //         // Set your Stripe secret key
    //         Stripe::setApiKey(config('services.stripe.secret'));

    //         $user = Auth::user();

    //         // Create a Checkout Session
    //         $session = Session::create([
    //             'payment_method_types' => ['card'],
    //             'line_items' => [[
    //                 'price_data' => [
    //                     'currency' => 'inr',
    //                     'product_data' => [
    //                         'name' => ucfirst($plan->type) . ' Plan',
    //                     ],
    //                     'unit_amount' => $plan->amount * 100, // Convert to cents
    //                 ],
    //                 'quantity' => 1,
    //             ]],
    //             'mode' => 'payment',
    //             'success_url' => route('checkout.success', ['plan' => $plan->id, 'session_id' => '{CHECKOUT_SESSION_ID}']),
    //             'cancel_url' => route('checkout.cancel', ['plan' => $plan->id]),
    //             'customer_email' => $user->email, 
    //             'metadata' => [
    //                 'plan_id' => $plan->id,
    //                 'user_id' => $user->id,
    //             ],
    //         ]);

    //         return redirect($session->url);

    //     } catch (ApiErrorException $e) {
    //         Log::error('Stripe Error: ' . $e->getMessage());
    //         return redirect()->route('checkout', $plan->id)
    //             ->with('error', 'Unable to process payment. Please try again.');
    //     }
    // }

    // public function handleSuccess(Request $request, Plan $plan)
    // {
    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     try {
    //         $session = Session::retrieve($request->session_id);
    //         $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);

    //         $user = Auth::user();


    //         Transaction::create([
    //             'user_id' => $user->id,
    //             'amount' => $plan->amount,
    //             'payment_method_id' => $paymentIntent->payment_method,
    //             'status' => $paymentIntent->status,
    //             'type' => 'membership',
    //             'method' => 'card'
    //         ]);


    //         Membership::create([
    //             'payments_method_id' => $paymentIntent->payment_method,
    //             'plan_id' => $plan->id,
    //             'user_id' => $user->id,
    //             'has_access' => $paymentIntent->status === 'succeeded' ? 1 : 0,
    //             'start_date' => now(),
    //             'end_date' => now()->addMinutes($plan->duration),
    //             'renewed_at' => now(),
    //         ]);

    //         return redirect()->route('dashboard')
    //             ->with('payment_success', 'Payment successful! Your membership is now active.');

    //     } catch (\Exception $e) {
    //         Log::error('Stripe Success Callback Error: ' . $e->getMessage());
    //         return redirect()->route('dashboard')
    //             ->with('error', 'There was a problem confirming your payment. Please contact support.');
    //     }
    // }

    // public function handleCancel(Request $request, Plan $plan)
    // {
    //     return redirect()->route('memberships')
    //         ->with('payment_failed', 'Payment was cancelled. Please try again.');
    // }

}
