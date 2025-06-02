<?php

namespace App\Http\Controllers;

use App\Services\TransaksiService;
use Illuminate\Http\Request;

class transaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TransaksiService $transaksiService)
    {

        return $transaksiService->getThreeYearsTransactions($request);
    }

    public function getTransactionCurrentMonthWithTargetDatas(Request $request, TransaksiService $transaksiService)
    {
        return $transaksiService->getTransactionCurrentMonth($request);
    }

    public function getTransactionCurrentMonthWithTarget(Request $request, TransaksiService $transaksiService)
    {
        return $transaksiService->getTransactionCurrentMonthWithTarget($request);
    }
}
