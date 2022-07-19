<?php

namespace App\Http\Controllers;

use App\Models\Plan as ModelsPlan;
use Exception;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;
use Stripe\Plan;

class SubscriptionController extends Controller
{
    public function showPlanForm()
    {
        return view('stripe.plans.create');
    }
    public function savePlan(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $amount = ($request->amount * 100);

        try {
            $plan = Plan::create([
                'amount' => $amount,
                'currency' => $request->currency,
                'interval' => $request->billing_period,
                'interval_count' => $request->interval_count,
                'product' => [
                    'name' => $request->name
                ]
            ]);

            ModelsPlan::create([
                'plan_id' => $plan->id,
                'name' => $request->name,
                'price' => $plan->amount,
                'billing_method' => $plan->interval,
                'currency' => $plan->currency,
                'interval_count' => $plan->interval_count
            ]);

        }
        catch(Exception $ex){
            dd($ex->getMessage());
        }

        return "success";
    }
    public function allPlans()
    {
        $basic = ModelsPlan::where('name', 'basic')->first();
        $professional = ModelsPlan::where('name', 'professional')->first();
        $enterprise = ModelsPlan::where('name', 'enterprise')->first();
        return view('stripe.plans', compact( 'basic', 'professional', 'enterprise'));
    }
    public function checkout($planId)
    {
        $plan = ModelsPlan::where('plan_id', $planId)->first();
        if(! $plan){
            return back()->withErrors([
                'message' => 'Unable to locate the plan'
            ]);
        }

        return view('stripe.plans.checkout', [
            'plan' => $plan,
            'intent' => auth()->user()->createSetupIntent(),
        ]);
    }
    public function processPlan(Request $request)
    {
        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        $paymentMethod = null;
        $paymentMethod = $request->payment_method;
        if($paymentMethod != null){
            $paymentMethod = $user->addPaymentMethod($paymentMethod);
        }
        $plan = $request->plan_id;

        try {
            $user->newSubscription(
                'default', $plan
            )->create( $paymentMethod != null ? $paymentMethod->id: '');
        }
        catch(Exception $ex){
            return back()->withErrors([
                'error' => 'Unable to create subscription due to this issue '. $ex->getMessage()
            ]);
        }

        $request->session()->flash('alert-success', 'You are subscribed to this plan');
        return to_route('plans.checkout', $plan);
    }
    public function allSubscriptions()
    {
        if (auth()->user()->onTrial('default')) {
            dd('trial');
        }
        $subscriptions = Subscription::where('user_id', auth()->id())->get();
        return view('stripe.subscriptions.index', compact('subscriptions'));
    }
    public function cancelSubscriptions(Request $request)
    {
        $subscriptionName = $request->subscriptionName;
        if($subscriptionName){
            $user = auth()->user();
            $user->subscription($subscriptionName)->cancel();
            return 'subsc is canceled';
        }
    }
    public function resumeSubscriptions(Request $request)
    {
        $user = auth()->user();
        $subscriptionName = $request->subscriptionName;
        if($subscriptionName){
            $user->subscription($subscriptionName)->resume();
            return 'subsc is resumed';
        }
    }
}
