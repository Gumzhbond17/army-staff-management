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

        $maleCount   = $genderCounts['ຊາຍ'] ?? 0;
        $femaleCount = $genderCounts['ຍິງ'] ?? 0;

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

        return view('reports.index', compact(
            'totalEmployees', 'maleCount', 'femaleCount',
            'allStatuses', 'statusCounts',
            'bloodGroups', 'bloodCounts'
        ));
    }
}
