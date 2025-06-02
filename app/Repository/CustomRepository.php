<?php 

namespace App\Repository;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CustomRepository
{
    protected $model;

    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): Customer
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            throw new ModelNotFoundException("Customer with ID {$id} not found.");
        }
        return $customer;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Customer
    {   
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Customer
    {
        $customer = $this->findById($id);
        if (!$customer) {
            throw new ModelNotFoundException("Customer with ID {$id} not found.");
        }
        $customer->update($data);
        return $customer;
    }

    public function delete(int $id): bool
    {
        $customer = $this->findById($id);
        return $customer->delete();
    }
}