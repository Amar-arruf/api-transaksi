<?php

use App\Http\Controllers\CustomerController;
use App\Services\CustomerService;
use Illuminate\Http\Request;


test('create method calls customer service create with request', function () {
    // Arrange
    $request = new Request();
    $mockCustomerService = Mockery::mock(CustomerService::class);
    $mockCustomerService->shouldReceive('create')
        ->once()
        ->with($request)
        ->andReturn(['success' => true]);

    // Act
    $controller = new CustomerController();
    $response = $controller->create($request, $mockCustomerService);

    // Assert
    expect($response)->toBe(['success' => true]);
});

test('update method calls customer service update with id and request', function () {
    // Arrange
    $request = new Request();
    $id = 123;
    $mockCustomerService = Mockery::mock(CustomerService::class);
    $mockCustomerService->shouldReceive('update')
        ->once()
        ->with($id, $request)
        ->andReturn(['success' => true]);

    // Act
    $controller = new CustomerController();
    $response = $controller->update($request, $id, $mockCustomerService);

    // Assert
    expect($response)->toBe(['success' => true]);
});

afterEach(function () {
    Mockery::close();
});
