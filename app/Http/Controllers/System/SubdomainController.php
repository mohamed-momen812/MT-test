<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    use HttpResponses;
     public function store(Request $request){
        $tenant = Tenant::create(['id' => $request->subdomain]);
        $tenant->domains()->create(['domain' => "{$request->subdomain}.mt-test.test"]);
        return $this->success($tenant, 'Tenant created success');
    }
}
