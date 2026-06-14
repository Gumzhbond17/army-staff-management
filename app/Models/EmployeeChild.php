<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeChild extends Model
{
    protected $fillable = ['employee_id', 'first_name', 'last_name', 'dob', 'gender', 'note'];

    protected $casts = [
        'dob' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

