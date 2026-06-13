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
        'previous_units', 'discipline_record', 'biography',
    ];

    protected $casts = [
        'dob' => 'date',
        'join_revolution_date' => 'date',
        'join_army_date' => 'date',
        'candidate_party_date' => 'date',
        'full_party_date' => 'date',
        'current_rank_date' => 'date',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function workStatus()
    {
        return $this->belongsTo(WorkingStatus::class);
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
}
