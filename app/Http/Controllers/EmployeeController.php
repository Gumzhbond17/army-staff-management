<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Unit;
use App\Models\WorkingStatus;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * GET /employees
     */
    public function index(Request $request)
    {
        $query = Employee::with(['unit', 'workStatus'])->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('unit')) {
            $query->byUnit($request->unit);
        }

        if ($request->filled('retirement_status')) {
            $query->where('retirement_status', $request->retirement_status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        $employees = $query->paginate(20);

        return view('employees.index', compact('employees'));
    }

    /**
     * GET /employees/create
     */
    public function create()
    {
        return view('employees.add_form', [
            'units'        => Unit::orderBy('name', 'asc')->get(),
            'workStatuses' => WorkingStatus::orderBy('name', 'asc')->get(),
            'provinces'    => Province::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * POST /employees
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        // Validate children if any
        if ($request->filled('child_count') && $request->child_count > 0) {
            $request->validate([
                'children.*.first_name' => ['required', 'string', 'max:100'],
                'children.*.last_name'  => ['required', 'string', 'max:100'],
                'children.*.dob'        => ['nullable', 'date'],
                'children.*.gender'     => ['nullable', Rule::in(['ຊາຍ', 'ຍິງ'])],
                'children.*.note'       => ['nullable', 'string', 'max:255'],
            ]);
        }

        // Remove non-employee fields before creating
        $employeeData = $request->except(['children', 'child_count']);
        $employee = Employee::create($employeeData);

        // Save children
        if ($request->filled('child_count') && $request->child_count > 0) {
            foreach ($request->input('children', []) as $child) {
                $employee->children()->create($child);
            }
        }

        return redirect()->route('employees.index')
                         ->with('success', 'ເພີ່ມຂໍ້ມູນພະນັກງານສຳເລັດ');
    }

    /**
     * GET /employees/{id}
     */
    public function show(string $id)
    {
        $employee = Employee::with(['unit', 'workStatus', 'children'])->findOrFail($id);

        return view('employees.show', compact('employee'));
    }

    /**
     * GET /employees/{id}/edit
     */
    public function edit(string $id)
    {
        $employee = Employee::with('children')->findOrFail($id);

        return view('employees.edit_form', [
            'employee'     => $employee,
            'units'        => Unit::orderBy('name', 'asc')->get(),
            'workStatuses' => WorkingStatus::orderBy('name', 'asc')->get(),
            'provinces'    => Province::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * PUT /employees/{id}
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate($this->rules($id));

        // Validate children if any
        if ($request->filled('child_count') && $request->child_count > 0) {
            $request->validate([
                'children.*.first_name' => ['required', 'string', 'max:100'],
                'children.*.last_name'  => ['required', 'string', 'max:100'],
                'children.*.dob'        => ['nullable', 'date'],
                'children.*.gender'     => ['nullable', Rule::in(['ຊາຍ', 'ຍິງ'])],
                'children.*.note'       => ['nullable', 'string', 'max:255'],
            ]);
        }

        $employee->update($request->except(['children', 'child_count']));

        // Sync children: delete old, insert new
        $employee->children()->delete();
        if ($request->filled('child_count') && $request->child_count > 0) {
            foreach ($request->input('children', []) as $child) {
                $employee->children()->create($child);
            }
        }

        return redirect()->route('employees.index')
                         ->with('success', 'ແກ້ໄຂຂໍ້ມູນສຳເລັດ');
    }

    /**
     * DELETE /employees/{id}
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->children()->delete();
        $employee->delete();

        return redirect()->route('employees.index')
                         ->with('success', 'ລຶບຂໍ້ມູນສຳເລັດ');
    }

    /**
     * Shared validation rules
     */
    private function rules(string $id = null): array
    {
        return [
            // ໝວດ I
            'gender'         => ['nullable', Rule::in(['ຊາຍ', 'ຍິງ'])],
            'full_name'      => ['required', 'string', 'max:255'],
            'unit_id'        => ['required', 'integer', 'exists:units,id'],
            'dob'            => ['nullable', 'date'],
            'party_duty'     => ['nullable', 'string', 'max:255'],
            'command_duty'   => ['nullable', 'string', 'max:255'],
            'officer_code'   => ['nullable', 'string', 'max:12'],
            'id_card_number' => ['nullable', 'string', 'max:25'],
            'work_status_id' => ['required', 'integer', 'exists:working_statuses,id'],
            'blood_group'    => ['nullable', Rule::in(['A', 'B', 'O', 'AB'])],

            // ໝວດ II
            'birth_village'      => ['nullable', 'string', 'max:150'],
            'birth_district_id'  => ['nullable', 'integer', 'exists:districts,id'],
            'birth_province_id'  => ['nullable', 'integer', 'exists:provinces,id'],

            // ໝວດ III
            'current_village'     => ['nullable', 'string', 'max:150'],
            'current_district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'current_province_id' => ['nullable', 'integer', 'exists:provinces,id'],

            // ໝວດ IV
            'culture_level'    => ['nullable', 'string', 'max:100'],
            'theory_level'     => ['nullable', 'string', 'max:100'],
            'theory_from'      => ['nullable', 'string', 'max:255'],
            'profession_level' => ['nullable', 'string', 'max:100'],
            'profession_from'  => ['nullable', 'string', 'max:255'],

            // ໝວດ V
            'nationality'       => ['nullable', 'string', 'max:100'],
            'ethnicity_group'   => ['nullable', 'string', 'max:100'],
            'tribe'             => ['nullable', 'string', 'max:100'],
            'religion'          => ['nullable', 'string', 'max:100'],
            'class_before_1975' => ['nullable', 'string', 'max:100'],
            'class_after_1975'  => ['nullable', 'string', 'max:100'],

            // ໝວດ VI
            'join_revolution_date' => ['nullable', 'date'],
            'join_army_date'       => ['nullable', 'date'],
            'candidate_party_date' => ['nullable', 'date'],
            'full_party_date'      => ['nullable', 'date'],
            'current_rank_date'    => ['nullable', 'date'],

            // ໝວດ VII
            'parents_name'   => ['nullable', 'string', 'max:255'],
            'spouse_name'    => ['nullable', 'string', 'max:255'],
            'child_count'    => ['nullable', 'integer', 'min:0'],

            // ໝວດ VII.b
            'previous_units'    => ['nullable', 'string'],
            'discipline_record' => ['nullable', 'string'],

            // ໝວດ VIII
            'biography' => ['nullable', 'string'],
            'photo'     => ['nullable', 'string'],
        ];
    }
}
