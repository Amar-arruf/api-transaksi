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

    public function delete(int $id, CustomerService $customerService)
    {
        return $customerService->delete($id);
    }

    public function findById(int $id, CustomerService $customerService)
    {
        return $customerService->findById($id);
    }

    public function all(CustomerService $customerService)
    {
        return $customerService->all();
    }
}