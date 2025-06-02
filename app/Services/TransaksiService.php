<?php

namespace App\Services;

use App\Repository\SalesRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;


class TransaksiService 

{
    protected $salesRepository; 
    
    public function __construct(SalesRepository $salesRepository)
    {
        $this->salesRepository = $salesRepository;
    }

    public function getThreeYearsTransactions(Request $request)
    {
        $currentYear = Carbon::now()->year;

        $customerRequest = $request->has('customer') ? $request->input('customer') : null;  
        $salesRequest = $request->has('sales') ? $request->input('sales') : null;

        try {
            $transactions =  $this->salesRepository->getLastThreeYears(
                Carbon::now()->subYears(3),
                $customerRequest,
                $salesRequest
            );
                
            $formattedData = [];
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            foreach (range($currentYear, $currentYear - 2) as $year) {
                $yearData = [
                    'name' => $year,
                    'data' => []
                ];

                foreach ($months as $index => $month) {
                    $monthNumber = $index + 1;
                    
                    $monthData = $transactions->first(function($item) use ($year, $monthNumber) {
                        return $item->year == $year && $item->month == $monthNumber;
                    });

                    $yearData['data'][] = [
                        'x' => $month,
                        'y' => number_format($monthData ? $monthData->total_amount : 0, 2, '.', '')
                    ];
                }

                $formattedData[] = $yearData;
            }

            return response()->json([
                'customer' => $customerRequest,
                'sales' => $salesRequest,
                'items' => $formattedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'gagal mendapatkan data: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }


    public function getTransactionCurrentMonth(Request $request)
    {
        $year = $request->has('year') ? $request->input('year') : null;
        $salesName = $request->has('sales') ? $request->input('sales') : null;

        try {
            $transactions = $this->salesRepository->getMonthlyTargetAndTransactions($salesName);

            // Format the data for the response
            $formattedData = [];
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $category = ['Target', 'Revenue', 'Income'];

            foreach ($category as $cat) {
                $catData = [
                    'name' => $cat,
                    'data' => []
                ];
                foreach ($months as $index => $month) {
                    $monthNumber = $index + 1;

                    $monthData = $transactions->first(function($item) use ($monthNumber) {
                        return $item->month == $monthNumber;
                    });

                    if ($cat === 'Target') {
                        $value = $monthData ? number_format($monthData->target, 2, '.', '') : 0;
                    } elseif ($cat === 'Revenue') {
                        $value = $monthData ? number_format($monthData->revenue, 2, '.', '') : 0;
                    } else { // Income
                        $value = $monthData ? number_format($monthData->income , 2, '.', '') : 0;
                    }

                    $catData['data'][] = [
                        'x' => $month,
                        'y' => number_format($value, 2, '.', '')
                    ];
                }

                $formattedData[] = $catData;
            }

            
            return response()->json([
                'sales' => $salesName,
                'year' => $year ? $year : Carbon::now()->year,
                'items' => $formattedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'gagal mendapatkan data: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
       
    }

}