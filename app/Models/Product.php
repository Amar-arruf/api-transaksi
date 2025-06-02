<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'product_price',
        'selling_price',
    ];

    protected $table = 'products';


    public function salesOrderItems()
    {
        return $this->hasMany(SalesOrderItem::class, 'product_id', 'id');
    }


}
