<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    protected $table = 'customers';

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id', 'id');
    }
}
