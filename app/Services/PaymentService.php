<?php 

namespace App\Services;

class PaymentService
{
    // Simulate a successful payment
    public function charge($user, $amount)
    {
        // Simulate success for now
        return [
            'status' => 'success',
            'transaction_id' => 'fake_txn_' . uniqid(),
            'amount' => $amount,
        ];
    }
}