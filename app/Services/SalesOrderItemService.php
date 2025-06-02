<?php

namespace App\Services;
use App\Repository\SalesOrderItemRepository;
use Illuminate\Http\Request;

class SalesOrderItemService
{
    protected $repository;

    public function __construct(SalesOrderItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Request $request)
    {
        try {
            $data = $request->validate([
                'order_id' => 'required|numeric|exists:sales_orders,id',
                'product_id' => 'required|numeric|exists:products,id',
                'quantity' => 'required|numeric|min:1',
                'production_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
            ]);

            $salesOrderItem = $this->repository->create($data);

            return response()->json($salesOrderItem, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}