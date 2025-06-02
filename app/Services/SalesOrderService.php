<?php

namespace App\Services;
use App\Repository\SalesOrderRepository;
use Illuminate\Http\Request;

class SalesOrderService
{
    protected $repository;

    public function __construct(SalesOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Request $request)
    {
        try {
            $data = $request->validate([
                'reference_no' => 'required|string|max:255',
                'sales_id' => 'required|numeric|exists:sales,id',
                'customer_id' => 'required|numeric|exists:customers,id',
    
            ]);

            $salesOrder = $this->repository->create($data);

            return response()->json($salesOrder, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}