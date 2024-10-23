<?php 

namespace App\Services\PaymentServices;

class paddlePaymentService
{
    // Simulate a successful payment
    public function charge($user, $amount)
    {
        // Simulate success for now
        return [
            'payment_method' => 'paddle',
            'status' => 'success',
            'transaction_id' => 'fake_txn_' . uniqid(),
            'amount' => $amount,
        ];
    }
}