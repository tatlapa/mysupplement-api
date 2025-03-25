<?php

namespace App\Http\Controllers;

use Stripe\PaymentIntent;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe as StripeGateway;
use Exception;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $request->validate([
        ]);
    
        StripeGateway::setApiKey(config('services.stripe.secret'));
    
        try {
    
            $paymentIntent = PaymentIntent::create([
                    'amount' => 30000,
            'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
    
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du PaymentIntent: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Une erreur est survenue lors de l\'initiation du paiement.',
            ], 500);
        }
    
        return [
            'token' => (string) Str::uuid(),
            'client_secret' => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id
        ];
    }

    // public function completePayment(Request $request)
    // {
    //     $stripe = new StripeClient('sk_test_51R4yCHLm7mC4vi7yHpVj97NxZmGpdVpV6DEUxVHZOO800nPPVNvv7POtKW7hRw9shWYaYxObXep10DbO1GFKCEXu00WG4CqosL');

    //     $paymentIntentId = $request->input('paymentIntentId');

    //     if (!$paymentIntentId) {
    //         return response()->json(['error' => 'Payment Intent ID not found'], 400);
    //     }

    //     // Use the payment intent ID stored when initiating payment
    //     $paymentDetail = $stripe->paymentIntents->retrieve($paymentIntentId);

    //     if ($paymentDetail->status != 'succeeded') {
    //         return response()->json(['error' => 'Payment not successful'], 400);
    //     }

    //     return response()->json(['success' => true, 'message' => 'Payment completed successfully']);    }

    // public function failPayment(Request $request)
    // {
    //     // Log the failed payment if you wish
    // }
}