<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    protected $fillable = [
        'sales_id',
        'amount',
        'amount',
    ];

    protected $table = 'sales_targets';

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sales_id', 'id');
    }
}
