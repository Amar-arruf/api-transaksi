<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    protected $fillable = [
        'quantity',
        'product_id',
        'product_price',
        'selling_price',
        'order_id'
    ];

    protected $table = 'sales_orders_items';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function salesOrder()
    {
        return $this->hasMany(SalesOrder::class, 'order_id', 'id');
    }
}
