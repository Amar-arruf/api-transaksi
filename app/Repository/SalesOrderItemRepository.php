<?php

namespace App\Repository;
use App\Models\SalesOrderItem;

class SalesOrderItemRepository
{
    protected $model;

    public function __construct(SalesOrderItem $model)
    {
        $this->model = $model;
    }

    public function create(array $data): SalesOrderItem
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): SalesOrderItem
    {
        $salesOrderItem = $this->findById($id);
        if (!$salesOrderItem) {
            throw new \Exception("Sales Order Item not found");
        }
        $salesOrderItem->update($data);
        return $salesOrderItem;
    }

    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function findById(int $id): SalesOrderItem
    {
        return $this->model->findOrFail($id);
    }

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->all();
    }
}