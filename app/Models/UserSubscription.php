<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subscription_plan_id', 'start_date', 'end_date', 'status'];

    public function plan() {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
