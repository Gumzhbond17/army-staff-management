<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo', 'full_name', 'gender', 'dob', 'officer_code', 'id_card_number',
        'unit_id', 'work_status_id', 'party_duty', 'command_duty', 'blood_group',
        'birth_province_id', 'birth_district_id', 'birth_village',
        'current_province_id', 'current_district_id', 'current_village',
        'culture_level', 'theory_level', 'theory_from',
        'profession_level', 'profession_from',
        'nationality', 'ethnicity_group', 'tribe', 'religion',
        'class_before_1975', 'class_after_1975',
        'join_revolution_date', 'join_army_date', 'candidate_party_date',
        'full_party_date', 'current_rank_date',
        'parents_name', 'spouse_name',

        // BUG FIX #5: child_count was missing from $fillable.
        // Without this, Employee::create() / ->update() silently drops the
        // child_count value even if it arrives in $validated, so the column
        // in the DB always stayed 0.
        'child_count',

        'previous_units', 'discipline_record', 'biography',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'dob'                  => 'date',
        'join_revolution_date' => 'date',
        'join_army_date'       => 'date',
        'candidate_party_date' => 'date',
        'full_party_date'      => 'date',
        'current_rank_date'    => 'date',

        // BUG FIX #6: child_count should be cast to integer so comparisons
        // like ($employee->child_count > 0) work correctly in Blade.
        'child_count'          => 'integer',
    ];

    // ================================================================
    //  RELATIONSHIPS
    // ================================================================

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * BUG FIX #7 (show.blade.php): The show view calls $employee->workStatus
     * but the relationship is defined as workingStatus(). We add a workStatus()
     * alias here so both names work, preventing "Call to undefined relationship"
     * errors without having to rename every Blade reference.
     */
    public function workingStatus()
    {
        return $this->belongsTo(WorkingStatus::class, 'work_status_id');
    }

    // Alias so show.blade.php $employee->workStatus->name works too
    public function workStatus()
    {
        return $this->belongsTo(WorkingStatus::class, 'work_status_id');
    }

    public function birthProvince()
    {
        return $this->belongsTo(Province::class, 'birth_province_id');
    }

    public function birthDistrict()
    {
        return $this->belongsTo(District::class, 'birth_district_id');
    }

    public function currentProvince()
    {
        return $this->belongsTo(Province::class, 'current_province_id');
    }

    public function currentDistrict()
    {
        return $this->belongsTo(District::class, 'current_district_id');
    }

    public function children()
    {
        return $this->hasMany(EmployeeChild::class);
    }

    // ================================================================
    //  HELPERS
    // ================================================================

    /**
     * Returns the public URL of the employee's photo, or null.
     * Usage in Blade: $employee->photoUrl
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
}
