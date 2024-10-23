<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $userId = $request->user()->id; // Assuming the user is authenticated
        $planeId = $request->input('plan_id');

        $plan = SubscriptionPlan::findOrFail($planeId);

        DB::beginTransaction();
        try {

            // $paymentService = app(PaymentService::class);
            // $paymentResult = $paymentService->charge($userId, $plan->price);

            // Simulate charging the user for the plan using PaymentService
            $paymentService = new PaymentService();
            $paymentResult = $paymentService->charge($userId, $plan->price);

            $startDate = Carbon::now();
            $endDate = Carbon::now()->addDays($plan->duration_days);
            
            if($paymentResult['status'] === 'success') {


                UserSubscription::create([
                    'user_id' => $userId,
                    'subscription_plan_id' => $planeId,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                ]);

                DB::commit();

                return response()->json([
                    'message' => 'Subscription successful',
                    'data' => [
                        'user_id' => $userId,
                        'subscription_plan_id' => $planeId,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'transaction_id' => $paymentResult['transaction_id'],
                        'status' => 'active',
                    ]
                ]);
            }else{
                DB::rollBack();
                return response()->json([
                    'message' => 'Subscription failed',
                    'data' => [
                        'user_id' => $userId,
                        'subscription_plan_id' => $planeId,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'transaction_id' => $paymentResult['transaction_id'],
                        'status' => 'failed',
                    ]
                ]);
            
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
