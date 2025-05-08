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
use Carbon\Carbon;

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

        $this->handleMembership($charge,$user,$plan);
        

        //send payment invoice notification
        // $user->notify(new PaymentInvoiceNotification($charge));

        if ($charge->status !== 'succeeded') {
            return redirect()->back()->with('payment_failed', 'Payment failed. Please try again.');
        }
        return redirect()->back()->with('payment_success', 'Payment successful!');
    }

    private function handleMembership($charge, $user,$plan) {
        // Getting the active and upcoming memberships
        $active_memberships = $user->activeMemberships;
        $upcoming_memberships = $user->upcomingMemberships; 
        $startDate = now(); 

        if ($active_memberships->isNotEmpty() || $upcoming_memberships->isNotEmpty()) {
            // Get the latest end date from both active and upcoming memberships of the same type
            $relevantMemberships = collect();

            // if new plan is Base, consider both active and upcoming Base memberships then merge both
            if ($plan->type == 'Base Membership') {
                $relevantMemberships = $active_memberships->where('plan.type', 'Base Membership')
                    ->merge($upcoming_memberships->where('plan.type', 'Base Membership'));
            }
            // If new plan is Pro, consider both active and upcoming Pro memberships
            elseif ($plan->type == 'Pro Membership') {
                $relevantMemberships = $active_memberships->where('plan.type', 'Pro Membership')
                    ->merge($upcoming_memberships->where('plan.type', 'Pro Membership'));
            }

            if ($relevantMemberships->isNotEmpty()) {
                $latestEndDate = $relevantMemberships->sortByDesc('end_date')->first()->end_date;
                $startDate = $latestEndDate;
            }
            
            //if the curr plan is pro , there is active base membership and no pro membership is active , also no upcoming membership is there , then start it now
            if ($plan->type == 'Pro Membership' && $active_memberships->where('plan.type', 'Base Membership')->isNotEmpty() && $active_memberships->where('plan.type', 'Pro Membership')->isEmpty() && $upcoming_memberships->where('plan.type', 'Pro Membership')->isEmpty()) 
            {
                $startDate = now();
            }
        }

        $membership = Membership::create([
            'payments_method_id' => $charge->payment_method,
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'has_access' => $charge->status === 'succeeded' ? 1 : 0,
            'start_date' => $startDate,
            'end_date' => Carbon::parse($startDate, config('app.timezone'))->addMinutes($plan->duration),
            'renewed_at' => now(),
        ]);

        //to load the relationship immediately 
        $user->refresh();

        //calculating the max limit for the user according to  his borrowed quantity  
        $max_limit = $user->activeMemberships->max(fn($membership) => $membership->plan->max_books_limit);
        
        $pending_borrow_count = $user->borrows->filter(function ($borrow) {
            return ($borrow->type === 'borrow' && ($borrow->status === 'pending' || $borrow->status === 'borrowed')) ||
                ($borrow->type === 'return' && $borrow->status === 'pending');
        })->count();
        

        $user->book_limit = $max_limit - $pending_borrow_count;
        $user->save();

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
        // auth()->user()->notify(new PaymentInvoiceNotification($payment));

        if ($charge->status === 'failed') {
            return redirect()->back()->with('payment_failed', 'Payment failed. Please try again.');
        }


        return redirect()->back()->with('payment_success', 'Payment successful!');
    }
}
