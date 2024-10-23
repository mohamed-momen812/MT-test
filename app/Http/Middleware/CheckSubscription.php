<?php

namespace App\Http\Middleware;

use App\Models\UserSubscription;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Check if user has an active subscription
        $subscription = UserSubscription::where('user_id', $user->id)
                            ->where('status', 'active')
                            ->where('end_date', '>=', now())
                            ->first();

        if (!$subscription) {
            return Response::json(['message' => 'You do not have an active subscription.'], 403);
        }

        return $next($request);
    }
}
