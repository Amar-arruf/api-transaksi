<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = [
        'reference_no',
        'customer_id',
        'sales_id',
    ];

    protected $table = 'sales_orders';

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function salesOrderItems()
    {
        return $this->hasMany(SalesOrderItem::class, 'order_id', 'id');
    }
}
