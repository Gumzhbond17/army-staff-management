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

    /**
     * Age in whole years calculated from dob, or null if dob is empty.
     * Usage in Blade: $child->age
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->dob || $this->dob->isFuture()) {
            return null;
        }

        return $this->dob->age;
    }
}

