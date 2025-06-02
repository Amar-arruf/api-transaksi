<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class salesArea extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $table = 'sales_areas';

    public function sales()
    {
        return $this->hasMany(Sale::class, 'area_id', 'id');
    }
}
