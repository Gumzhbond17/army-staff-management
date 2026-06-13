<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Unit;
use App\Models\WorkingStatus;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * GET /employees
     */
    public function index(Request $request)
    {
        $query = Employee::with(['unit', 'workStatus']);

        if ($search = $request->get('search')) {
            $query->where('full_name', 'like', "%{$search}%");
        }

        $employees = $query->orderBy('full_name')->paginate(15)->withQueryString();

        return view('employees.index', compact('employees'));
    }

    /**
     * GET /employees/create
     */
    public function create()
    {
        return view('employees.add_form', [
            'units'        => Unit::orderBy('name','asc')->get(),
            'workStatuses' => WorkingStatus::orderBy('name','asc')->get(),
            'provinces'    => Province::orderBy('name','asc')->get(),
            'employee'     => new Employee(),
        ]);
    }

    /**
     * POST /employees
     */
    public function store(Request $request)
    {
        // Temporary debug — remove after fixing
        Log::info('=== STORE DEBUG ===', [
            'hasFile'        => $request->hasFile('photo'),
            'fileValid'      => $request->hasFile('photo') ? $request->file('photo')->isValid() : 'no file',
            'fileError'      => $request->hasFile('photo') ? $request->file('photo')->getError() : 'no file',
            'fileSize'       => $request->hasFile('photo') ? $request->file('photo')->getSize() : 'no file',
            'fileMime'       => $request->hasFile('photo') ? $request->file('photo')->getMimeType() : 'no file',
        ]);

        $validated = $request->validate($this->rules());

        if ($request->filled('child_count') && $request->child_count > 0) {
            $request->validate($this->childrenRules());
        }

        unset($validated['child_count']);

        try {
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
            }

            $employee = Employee::create($validated);
            $this->syncChildren($employee, $request);

        } catch (\Throwable $e) {
            Log::error('Employee store failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'ບໍ່ສາມາດບັນທຶກຂໍ້ມູນໄດ້: ' . $e->getMessage()]);
        }

        return redirect()->route('employees.index')
                        ->with('success', 'ເພີ່ມຂໍ້ມູນພະນັກງານສຳເລັດ');
    }

    /**
     * GET /employees/{employee}
     */
    public function show(Employee $employee)
    {
        $employee->load([
            'unit', 'workStatus',
            'birthProvince', 'birthDistrict',
            'currentProvince', 'currentDistrict',
            'children',
        ]);

        return view('employees.show', compact('employee'));
    }

    /**
     * GET /employees/{employee}/edit
     */
    public function edit(Employee $employee)
    {
        $employee->load(['children', 'birthDistrict', 'currentDistrict']);

        return view('employees.edit_form', [
            'employee'     => $employee,
            'units'        => Unit::orderBy('name','asc')->get(),
            'workStatuses' => WorkingStatus::orderBy('name','asc')->get(),
            'provinces'    => Province::orderBy('name','asc')->get(),
        ]);
    }

    /**
     * PUT/PATCH /employees/{employee}
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate($this->rules($employee->id));

        if ($request->filled('child_count') && $request->child_count > 0) {
            $request->validate($this->childrenRules());
        }

        unset($validated['child_count']);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee->update($validated);

        // Replace children records
        $employee->children()->delete();
        $this->syncChildren($employee, $request);

        return redirect()->route('employees.index')
                         ->with('success', 'ແກ້ໄຂຂໍ້ມູນພະນັກງານສຳເລັດ');
    }

    /**
     * DELETE /employees/{employee}
     */
    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->children()->delete();
        $employee->delete();

        return redirect()->route('employees.index')
                         ->with('success', 'ລຶບຂໍ້ມູນພະນັກງານສຳເລັດ');
    }

    /**
     * Validation rules for create/update.
     */
    private function rules(?int $employeeId = null): array
    {
        return [
            'photo'              => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'full_name'          => ['required', 'string', 'max:150'],
            'gender'             => ['nullable', Rule::in(['male', 'female'])],
            'dob'                => ['nullable', 'date'],
            'officer_code'       => ['nullable', 'string', 'max:12'],
            'id_card_number'     => ['nullable', 'string', 'max:25'],
            'unit_id'            => ['required', 'exists:units,id'],
            'work_status_id'     => ['required', 'exists:working_statuses,id'],
            'party_duty'         => ['nullable', 'string', 'max:255'],
            'command_duty'       => ['nullable', 'string', 'max:255'],
            'blood_group'        => ['nullable', Rule::in(['A', 'B', 'O', 'AB'])],

            'birth_province_id'  => ['nullable', 'exists:provinces,id'],
            'birth_district_id'  => ['nullable', 'exists:districts,id'],
            'birth_village'      => ['nullable', 'string', 'max:255'],

            'current_province_id' => ['nullable', 'exists:provinces,id'],
            'current_district_id' => ['nullable', 'exists:districts,id'],
            'current_village'     => ['nullable', 'string', 'max:255'],

            'culture_level'      => ['nullable', 'string', 'max:255'],
            'theory_level'       => ['nullable', 'string', 'max:255'],
            'theory_from'        => ['nullable', 'string', 'max:255'],
            'profession_level'   => ['nullable', 'string', 'max:255'],
            'profession_from'    => ['nullable', 'string', 'max:255'],

            'nationality'        => ['nullable', 'string', 'max:255'],
            'ethnicity_group'    => ['nullable', 'string', 'max:255'],
            'tribe'              => ['nullable', 'string', 'max:255'],
            'religion'           => ['nullable', 'string', 'max:255'],
            'class_before_1975'  => ['nullable', 'string', 'max:255'],
            'class_after_1975'   => ['nullable', 'string', 'max:255'],

            'join_revolution_date' => ['nullable', 'date'],
            'join_army_date'       => ['nullable', 'date'],
            'candidate_party_date' => ['nullable', 'date'],
            'full_party_date'      => ['nullable', 'date'],
            'current_rank_date'    => ['nullable', 'date'],

            'parents_name'       => ['nullable', 'string', 'max:255'],
            'spouse_name'        => ['nullable', 'string', 'max:255'],

            'child_count'        => ['nullable', 'integer', 'min:0', 'max:10'],

            'previous_units'     => ['nullable', 'string'],
            'discipline_record'  => ['nullable', 'string'],
            'biography'          => ['nullable', 'string'],
        ];
    }

    /**
     * Validation rules for the dynamic children rows.
     */
    private function childrenRules(): array
    {
        return [
            'children.*.first_name' => ['required', 'string', 'max:100'],
            'children.*.last_name'  => ['required', 'string', 'max:100'],
            'children.*.dob'        => ['nullable', 'date'],
            'children.*.gender'     => ['nullable', Rule::in(['male', 'female'])],
            'children.*.note'       => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Persist children rows for an employee.
     */
    private function syncChildren(Employee $employee, Request $request): void
    {
        if (!$request->filled('child_count') || $request->child_count <= 0) {
            return;
        }

        foreach ($request->input('children', []) as $child) {
            if (empty($child['first_name']) && empty($child['last_name'])) {
                continue;
            }
            $employee->children()->create($child);
        }
    }
}
