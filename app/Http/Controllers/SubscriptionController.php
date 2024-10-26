<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Services\PaymentService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    use HttpResponses;
    public function subscribe(Request $request)
    {

        $userId = $request->user()->id;

        $planId = $request->plan_id;

        $plan = SubscriptionPlan::find($planId);

        if(!$plan) {
            return $this->error('', 'No plane found with this id', 404);
        }

        DB::beginTransaction();
        try {

            // Simulate charging the user for the plan using PaymentService
            // using app to resolve payment service
            $paymentService = app('PaymentService');
            // $paymentService = new PaymentService();

            $paymentResult = $paymentService->charge($userId, $plan->price);

            $startDate = Carbon::now();
            $endDate = Carbon::now()->addDays($plan->duration_days);

            if($paymentResult['status'] === 'success') {
                UserSubscription::create([
                    'user_id' => $userId,
                    'subscription_plan_id' => $planId,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                ]);

                DB::commit();

                return $this->success([
                        'user_id' => $userId,
                        'subscription_plan_id' => $planId,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'transaction_id' => $paymentResult['transaction_id'],
                        'status' => 'active',
                ], 'Subscription successful');
            }else{
                DB::rollBack();
                return $this->error('', 'Subscription failed', 403);

            }
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->error('', $e->getMessage(), 400);
        }
    }
}
