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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class, 'order_id', 'id');
    }
}
