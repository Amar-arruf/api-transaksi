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

    public function getTransactionCurrentMonthWithTarget(Request $request)
    {
        $month = $request->has('month') ? $request->input('month') : null;
        $isUnderPerform = $request->has('is_underperform') ? $request->input('is_underperform') : null;

        try {
            $transactions = $this->salesRepository->getSSalesWithTargetOneMonth($month, $isUnderPerform);
            
            $formattedItems = $transactions->map(function ($item) {
            $revenue = floatval($item->revenue);
            $target = floatval($item->target);
            $percentage = $target > 0 ? ($revenue / $target * 100) : 0;

            // Format abbreviation
            $revenueAbbr = $revenue >= 1000000000 
                ? number_format($revenue / 1000000000, 2) . 'B'
                : number_format($revenue / 1000000, 2) . 'M';

            $targetAbbr = $target >= 1000000000
                ? number_format($target / 1000000000, 2) . 'B'
                : number_format($target / 1000000, 2) . 'M';

            return [
                'sales' => $item->sales,
                'revenue' => [
                'amount' => number_format($revenue, 2, '.', ''),
                'abbreviation' => $revenueAbbr
                ],
                'target' => [
                'amount' => number_format($target, 2, '.', ''),
                'abbreviation' => $targetAbbr
                ],
                'percentage' => number_format($percentage, 2)
            ];
            });

            return response()->json([
                'is_underperform' => $isUnderPerform,
                'month' => $month ? $month : Carbon::now()->locale('id')->translatedFormat('F Y'),
                'items' => $formattedItems
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