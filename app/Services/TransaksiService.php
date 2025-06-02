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
            // Use query builder with joins instead of eager loading the whole dataset
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
                    
                    // Find the corresponding transaction data
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
}