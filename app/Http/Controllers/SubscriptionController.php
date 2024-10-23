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
        
        $userId = $request->user()->id; 
        
        $planeId = $request->input('plan_id');

        $plan = SubscriptionPlan::findOrFail($planeId);
        DB::beginTransaction();
        try {

            // Simulate charging the user for the plan using PaymentService
            // using app to resolve payment service
            $paymentService = app('PaymentService');
            // $paymentService = new PaymentService();

            $paymentResult = $paymentService->charge($userId, $plan->price);

            dd($paymentResult['payment_method']);


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
