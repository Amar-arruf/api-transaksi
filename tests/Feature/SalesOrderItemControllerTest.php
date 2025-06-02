<?php

use App\Http\Controllers\SalesOrderItemController;
use App\Services\SalesOrderItemService;
use Illuminate\Http\Request;

test('create data on method Controller create', function () {
    $request = new Request([
        'order_id' => 1,
        'product_id' => 2,
        'quantity' => 5,
        'production_price' => 50,
        'selling_price' => 75
    ]);

    $mockService = Mockery::mock(SalesOrderItemService::class);
    $mockService->shouldReceive('create')
        ->once()
        ->with($request)
        ->andReturn(response()->json([
            'order_id' => 1,
            'product_id' => 2,
            'quantity' => 5,
            'production_price' => 50,
            'selling_price' => 75
        ], 201));

    $controller = new SalesOrderItemController();
    $response = $controller->create($request, $mockService);

    expect($response->getStatusCode())->toBe(201);
    expect(json_decode($response->getContent(), true))->toBe([
        'order_id' => 1,
        'product_id' => 2,
        'quantity' => 5,
        'production_price' => 50,
        'selling_price' => 75
    ]);
});

afterEach(function () {
    Mockery::close();
});
