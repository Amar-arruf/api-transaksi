<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    public $timestamps = false; 
    
    protected $fillable = [
        'quantity',
        'product_id',
        'production_price',
        'selling_price',
        'order_id'
    ];

    protected $table = 'sales_order_items';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'id');
    }
}
