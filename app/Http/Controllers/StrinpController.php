<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StrinpController extends Controller
{
    public function checkout() {

          $product = [
            'id' => 1,
            'name' => 'Premium Headphones',
            'price' => 4999, // amount in cents (₹ or $ depending on Stripe setup)
            'currency' => 'usd'];

        return view('product.product', compact('product'));
    }

    public function session(Request $request) {
       Stripe::setApiKey(config('services.stripe.secret'));
        
       $session = Session::create([
        'payment_method_types' => ['card'], 'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Test Product',
                    ],
                    'unit_amount' => 2000, // $20
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('success'),
            'cancel_url' => route('cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        return view('product.success');
    }

    public function cancel()
    {
        return view('product.cancel');
    }
}
