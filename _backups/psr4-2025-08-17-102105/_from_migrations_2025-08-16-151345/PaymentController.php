<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function paymentPage()
    {
        return view('payments.payment');
    }

    public function processStripe(Request $request)
    {
        // TODO: integrate real Stripe API here
        $user = Auth::user();

        // Simulate payment success
        $payment = Payment::create([
            'user_id' => $user->id,
            'payment_method' => 'stripe',
            'transaction_id' => uniqid('stripe_'),
            'amount' => $request->amount,
            'status' => 'completed',
        ]);

        return redirect()->route('payments.success')->with('success', 'Stripe payment processed successfully.');
    }

    public function processPayTabs(Request $request)
    {
        // TODO: integrate real PayTabs API here
        $user = Auth::user();

        // Simulate payment success
        $payment = Payment::create([
            'user_id' => $user->id,
            'payment_method' => 'paytabs',
            'transaction_id' => uniqid('paytabs_'),
            'amount' => $request->amount,
            'status' => 'completed',
        ]);

        return redirect()->route('payments.success')->with('success', 'PayTabs payment processed successfully.');
    }

    public function success()
    {
        return view('payments.success');
    }
}
