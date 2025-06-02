<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $table = 'user_roles';

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
