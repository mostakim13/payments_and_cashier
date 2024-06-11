<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionController extends Controller
{
    public function createSubscription(Request $request)
    {
        $user = Auth::user();

        try {
            $user->newSubscription('default', env('STRIPE_PRICE_ID'))->create($request->paymentMethod);
        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('home')]
            );
        }

        return redirect('/subscribe')->with('success', 'Subscription created successfully.');
    }
}
