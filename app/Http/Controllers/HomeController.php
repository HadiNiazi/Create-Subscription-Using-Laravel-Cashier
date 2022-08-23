<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Charge;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        return view('home', [
            'intent' => $user->createSetupIntent(),
        ]);
    }
    public function singleCharge(Request $request)
    {
        $amount = $request->amount;
        $amount = $amount * 100;
        $paymentMethod = $request->payment_method;

        $user = auth()->user();
        $user->createOrGetStripeCustomer();

        if ($paymentMethod != null) {
            $paymentMethod = $user->addPaymentMethod($paymentMethod);
        }

        $user->charge($amount, $paymentMethod);

        return to_route('home');
    }
}
