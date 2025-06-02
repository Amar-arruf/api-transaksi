<?php

namespace App\Http\Controllers;

use App\Services\SalesOrderItemService;
use Illuminate\Http\Request;

class SalesOrderItemController extends Controller
{
    public function create(Request $request, SalesOrderItemService $salesOrderItemService)
    {
        return $salesOrderItemService->create($request);
    }
}
