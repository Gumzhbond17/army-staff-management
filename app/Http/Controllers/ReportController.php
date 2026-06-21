<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\WorkingStatus;

class ReportController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();

        // Gender stats
        $genderCounts = Employee::selectRaw('gender, COUNT(*) as count')
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->pluck('count', 'gender');

        $maleCount   = $genderCounts['male'] ?? 0;
        $femaleCount = $genderCounts['female'] ?? 0;

        // Working status stats
        $allStatuses  = WorkingStatus::orderBy('name')->get();
        $statusCounts = Employee::selectRaw('work_status_id, COUNT(*) as count')
            ->groupBy('work_status_id')
            ->pluck('count', 'work_status_id');

        // Blood group stats
        $bloodGroups = ['A', 'B', 'O', 'AB'];
        $bloodCounts = Employee::selectRaw('blood_group, COUNT(*) as count')
            ->whereNotNull('blood_group')
            ->groupBy('blood_group')
            ->pluck('count', 'blood_group');

        // Current province stats
        $currentProvinceStats = Employee::join('provinces as cp', 'employees.current_province_id', '=', 'cp.id')
            ->selectRaw('cp.id, cp.name, COUNT(*) as count')
            ->groupBy('cp.id', 'cp.name')
            ->orderByDesc('count')
            ->get();

        // Birth province stats
        $birthProvinceStats = Employee::join('provinces as bp', 'employees.birth_province_id', '=', 'bp.id')
            ->selectRaw('bp.id, bp.name, COUNT(*) as count')
            ->groupBy('bp.id', 'bp.name')
            ->orderByDesc('count')
            ->get();

        // Current district stats
        $currentDistrictStats = Employee::join('districts as cd', 'employees.current_district_id', '=', 'cd.id')
            ->selectRaw('cd.id, cd.name, COUNT(*) as count')
            ->groupBy('cd.id', 'cd.name')
            ->orderByDesc('count')
            ->get();

        // Birth district stats
        $birthDistrictStats = Employee::join('districts as bd', 'employees.birth_district_id', '=', 'bd.id')
            ->selectRaw('bd.id, bd.name, COUNT(*) as count')
            ->groupBy('bd.id', 'bd.name')
            ->orderByDesc('count')
            ->get();

        return view('reports.index', compact(
            'totalEmployees', 'maleCount', 'femaleCount',
            'allStatuses', 'statusCounts',
            'bloodGroups', 'bloodCounts',
            'currentProvinceStats', 'birthProvinceStats',
            'currentDistrictStats', 'birthDistrictStats'
        ));
    }
}
