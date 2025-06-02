<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'area_id',    
    ];

    protected $table = 'sales';

    public function salesOrder()
    {
        return $this->hasMany(SalesOrder::class, 'sales_id', 'id');
    }

    public function salesTarget()
    {
        return $this->hasMany(SalesTarget::class, 'sales_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }   

    public function salesArea()
    {
        return $this->belongsTo(SalesArea::class, 'area_id', 'id');
    }
}
