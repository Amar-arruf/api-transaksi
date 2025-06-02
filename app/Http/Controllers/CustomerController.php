<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function create(Request $request, CustomerService $customerService)
    {
        return $customerService->create($request);
    }


    public function update(Request $request, int $id, CustomerService $customerService)
    {

        return $customerService->update($id, $request);
    }
}