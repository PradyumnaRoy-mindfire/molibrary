<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
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
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
class PaymentController extends Controller
{
    //

    // public function showCheckoutForm(Plan $plan)
    // {
    //     return view('payment.checkout', compact('plan'));
    // }

    // public function processCheckout(Request $request, Plan $plan)
    // {
    //     $user = Auth::user();
    //     $amount = $request->amount;

    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     $charge = Charge::create([
    //         'amount' => $amount * 100,
    //         'currency' => 'inr',
    //         'description' => $plan->type . " Plan",
    //         'source' => $request->stripeToken,
    //         'receipt_email' => $request->email,
    //     ]);

    //     // saving to transactions table
    //     Transaction::create([
    //         'user_id' => $user->id ,
    //         'amount' => $amount ,
    //         'payment_method_id' => $charge->payment_method ,
    //         'status' => $charge->status ,
    //         'type' => 'membership' ,
    //         'method' => 'card'
    //     ]);

    //     // create record to membership table for the user 
    //     Membership::create([
    //         'payments_method_id' => $charge->payment_method,
    //         'plan_id' => $plan->id,
    //         'user_id' => $user->id,
    //         'has_access' => $charge->status === 'succeeded' ? 1 : 0 ,
    //         'start_date' => now(),
    //         'end_date' => now()->addMinutes($plan->duration),
    //         'renewed_at' => now(),
    //     ]);

    //     if ($charge->status === 'failed') {
    //         return redirect()->back()->with('payment_failed', 'Payment failed. Please try again.');
    //     }
    //     return redirect()->back()->with('payment_success', 'Payment successful!');
    // }

    public function showCheckoutForm(Plan $plan)
    {
        return view('payment.checkout', compact('plan'));
    }
    
    public function processCheckout(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'card_number' => 'required|string',
            'card_expiry' => 'required|string|size:5',
            'card_cvc' => 'required|string|min:3|max:4',
        ]);
        
        $expiry = explode('/', $validated['card_expiry']);
        $expiryMonth = trim($expiry[0]);
        $expiryYear = '20' . trim($expiry[1]);
        
        $cardNumber = str_replace(' ', '', $validated['card_number']);
        
        try {
            // Set your Stripe secret key
            Stripe::setApiKey(config('services.stripe.secret'));
            
            // Create or retrieve the customer
            $user = auth()->user();
            
            if (!$user->stripe_id) {
                $customer = Customer::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);
                
                // Save the Stripe customer ID to the user model
                $user->stripe_id = $customer->id;
                $user->save();
            } else {
                $customer = Customer::retrieve($user->stripe_id);
            }
            
            // Create a payment method using the card details
            $paymentMethod = PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'number' => $cardNumber,
                    'exp_month' => $expiryMonth,
                    'exp_year' => $expiryYear,
                    'cvc' => $validated['card_cvc'],
                ],
                'billing_details' => [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ],
            ]);
            
            // Attach payment method to customer
            $paymentMethod->attach([
                'customer' => $customer->id,
            ]);
            
            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $plan->amount * 100, // Convert to cents
                'currency' => 'inr',
                'customer' => $customer->id,
                'payment_method' => $paymentMethod->id,
                'confirm' => true,
                'description' => 'Payment for ' . ucfirst($plan->type) . ' Plan',
                'metadata' => [
                    'plan_id' => $plan->id,
                    'plan_type' => $plan->type
                ],
            ]);
            
            if ($paymentIntent->status === 'succeeded') {
                $user->subscriptions()->create([
                    'plan_id' => $plan->id,
                    'payment_id' => $paymentIntent->id,
                    'amount' => $plan->amount,
                    'status' => 'active',
                    'starts_at' => now(),
                    'expires_at' => now()->addMonths($plan->duration_months),
                ]);
                
                return redirect()->route('dashboard')
                    ->with('success', 'Thank you! Your payment was successful and your subscription is now active.');
            }
            
            // Handle other payment statuses
            return redirect()->route('checkout', $plan->id)
                ->with('error', 'Payment processing: ' . $paymentIntent->status . '. Please try again.');
                
        } catch (CardException $e) {
            // Handle card errors
            Log::error('Stripe Card Error: ' . $e->getMessage());
            return redirect()->route('checkout', $plan->id)
                ->with('error', 'Card error: ' . $e->getError()->message);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Stripe Error: ' . $e->getMessage());
            return redirect()->route('checkout', $plan->id)
                ->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }


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
