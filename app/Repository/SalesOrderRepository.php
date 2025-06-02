<?php

namespace App\Repository;

use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Collection;

class SalesOrderRepository
{
    protected $model;

    public function __construct(SalesOrder $model)
    {
        $this->model = $model;
    }

    public function create(array $data): SalesOrder
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): SalesOrder
    {
        $salesOrder = $this->findById($id);
        if (!$salesOrder) {
            throw new \Exception("Sales Order not found");
        }
        $salesOrder->update($data);
        return $salesOrder;
    }

    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function findById(int $id): SalesOrder
    {
        return $this->model->findOrFail($id);
    }

    public function all(): Collection
    {
        return $this->model->all();
    }
}