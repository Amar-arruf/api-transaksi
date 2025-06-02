<?php

namespace App\Http\Controllers;

use App\Services\SalesOrderService;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function create(Request $request, SalesOrderService $salesOrderService)
    {
        return $salesOrderService->create($request);
    }
}
