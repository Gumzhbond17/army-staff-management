<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Model
{
    /**
     * UUID primary key (not auto-increment)
     */
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        // ໝວດ I
        'gender',
        'full_name',
        'unit',
        'dob',
        'party_duty',
        'command_duty',
        'officer_code',
        'id_card_number',
        'retirement_status',
        'blood_group',

        // ໝວດ II
        'birth_village',
        'birth_district_id',    // ← was birth_district
        'birth_province_id',    // ← was birth_province

        // ໝວດ III
        'current_village',
        'current_district_id',  // ← was current_district
        'current_province_id',  // ← was current_province

        // ໝວດ IV
        'culture_level',
        'theory_level',
        'theory_from',
        'profession_level',
        'profession_from',

        // ໝວດ V
        'nationality',
        'ethnicity_group',
        'tribe',
        'religion',
        'class_before_1975',
        'class_after_1975',

        // ໝວດ VI
        'join_revolution_date',
        'join_army_date',
        'candidate_party_date',
        'full_party_date',
        'current_rank_date',

        // ໝວດ VII
        'parents_name',
        'spouse_name',
        'children_count',

        // ໝວດ VII.b
        'previous_units',
        'discipline_record',

        // ໝວດ VIII
        'biography',
        'photo',
    ];

    protected $casts = [
        'dob'                  => 'date',
        'join_revolution_date' => 'date',
        'join_army_date'       => 'date',
        'candidate_party_date' => 'date',
        'full_party_date'      => 'date',
        'current_rank_date'    => 'date',
        'children_count'       => 'integer',
    ];

    public function birthDistrict()
    {
        return $this->belongsTo(District::class, 'birth_district_id');
    }

    public function birthProvince()
    {
        return $this->belongsTo(Province::class, 'birth_province_id');
    }

    public function currentDistrict()
    {
        return $this->belongsTo(District::class, 'current_district_id');
    }

    public function currentProvince()
    {
        return $this->belongsTo(Province::class, 'current_province_id');
    }

    // app/Models/Employee.php

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function workStatus()
    {
        return $this->belongsTo(WorkingStatus::class, 'work_status_id');
    }

    /**
     * Auto-generate UUID on creation
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Employee $model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // -------- Scopes --------

    public function scopeWorking(Builder $query)
    {
        return $query->where('retirement_status', 'working');
    }

    public function scopeRetired(Builder $query)
    {
        return $query->where('retirement_status', 'retired');
    }

    public function scopeByUnit(Builder $query, string $unit)
    {
        return $query->where('unit', $unit);
    }

    public function scopeSearch(Builder $query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('full_name', 'like', "%{$keyword}%")
              ->orWhere('officer_code', 'like', "%{$keyword}%")
              ->orWhere('id_card_number', 'like', "%{$keyword}%");
        });
    }
}
