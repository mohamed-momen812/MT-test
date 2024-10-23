<?php 

namespace App\Services\PaymentServices;

class stripePaymentService
{
    // Simulate a successful payment
    public function charge($user, $amount)
    {
        // Simulate success for now
        return [
            'payment_method' => 'stripe',
            'status' => 'success',
            'transaction_id' => 'fake_txn_' . uniqid(),
            'amount' => $amount,
        ];
    }
}