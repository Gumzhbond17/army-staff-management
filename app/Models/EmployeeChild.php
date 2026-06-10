<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeChild extends Model
{
    protected $fillable = ['employee_id', 'first_name', 'last_name', 'dob', 'gender', 'note'];

    public function children()
    {
        return $this->hasMany(EmployeeChild::class);
    }
}

