<?php

use App\Http\Controllers\SalesOrderController;
use App\Services\SalesOrderService;
use Illuminate\Http\Request;

test('create data on method data', function () {
    $request = new Request(
        [
            'id' => 1,
            'name' => 'Test Item',
            'quantity' => 2,
            'price' => 100.00
        ]
        );
    $mockService = Mockery::mock(SalesOrderService::class);
    $mockService->shouldReceive('create')
        ->once()
        ->with($request)
        ->andReturn(['success' => true,
            'data' => [
                'id' => 1,
                'name' => 'Test Item',
                'quantity' => 2,
                'price' => 100.00
            ]
        ]);
    
    $response = new SalesOrderController();
    $result = $response->create($request, $mockService);
    expect($result)->toBe(['success' =>true,
        'data' => [
            'id' => 1,
            'name' => 'Test Item',
            'quantity' => 2,
            'price' => 100.00
        ]
    ]);

});

afterEach(function () {
    Mockery::close();
}); 
