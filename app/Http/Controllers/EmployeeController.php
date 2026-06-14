<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Province;
use App\Models\Unit;
use App\Models\WorkingStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // ================================================================
    //  PRIVATE HELPERS
    // ================================================================

    private function rules(?int $employeeId = null): array
    {
        return [
            'full_name'            => ['required', 'string', 'max:255'],
            'gender'               => ['nullable', 'in:male,female'],
            'dob'                  => ['nullable', 'date'],
            'officer_code'         => ['nullable', 'string', 'max:12'],
            'id_card_number'       => ['nullable', 'string', 'max:25'],
            'unit_id'              => ['required', 'exists:units,id'],
            'work_status_id'       => ['required', 'exists:working_statuses,id'],
            'party_duty'           => ['nullable', 'string', 'max:255'],
            'command_duty'         => ['nullable', 'string', 'max:255'],
            'blood_group'          => ['nullable', 'in:A,B,O,AB'],

            // BUG FIX #1: photo must use 'sometimes' so it is only validated
            // when actually present in the request. Without 'sometimes',
            // Laravel validates the 'photo' key even on update when no new
            // file is uploaded, causing a "The photo must be an image" error.
            'photo'                => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'birth_province_id'    => ['nullable', 'exists:provinces,id'],
            'birth_district_id'    => ['nullable', 'exists:districts,id'],
            'birth_village'        => ['nullable', 'string', 'max:255'],
            'current_province_id'  => ['nullable', 'exists:provinces,id'],
            'current_district_id'  => ['nullable', 'exists:districts,id'],
            'current_village'      => ['nullable', 'string', 'max:255'],
            'culture_level'        => ['nullable', 'string', 'max:255'],
            'theory_level'         => ['nullable', 'string', 'max:255'],
            'theory_from'          => ['nullable', 'string', 'max:255'],
            'profession_level'     => ['nullable', 'string', 'max:255'],
            'profession_from'      => ['nullable', 'string', 'max:255'],
            'nationality'          => ['nullable', 'string', 'max:100'],
            'ethnicity_group'      => ['nullable', 'string', 'max:100'],
            'tribe'                => ['nullable', 'string', 'max:100'],
            'religion'             => ['nullable', 'string', 'max:100'],
            'class_before_1975'    => ['nullable', 'string', 'max:100'],
            'class_after_1975'     => ['nullable', 'string', 'max:100'],
            'join_revolution_date' => ['nullable', 'date'],
            'join_army_date'       => ['nullable', 'date'],
            'candidate_party_date' => ['nullable', 'date'],
            'full_party_date'      => ['nullable', 'date'],
            'current_rank_date'    => ['nullable', 'date'],
            'parents_name'         => ['nullable', 'string', 'max:255'],
            'spouse_name'          => ['nullable', 'string', 'max:255'],

            // BUG FIX #2: child_count must be included in validation rules
            // so it passes through $request->validate() without being stripped.
            'child_count'          => ['nullable', 'integer', 'min:0', 'max:20'],

            'previous_units'       => ['nullable', 'string'],
            'discipline_record'    => ['nullable', 'string'],
            'biography'            => ['nullable', 'string'],
        ];
    }

    private function childrenRules(): array
    {
        return [
            'children'               => ['nullable', 'array'],
            'children.*.first_name'  => ['nullable', 'string', 'max:100'],
            'children.*.last_name'   => ['nullable', 'string', 'max:100'],
            'children.*.dob'         => ['nullable', 'date'],
            'children.*.gender'      => ['nullable', 'in:male,female'],
            'children.*.note'        => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Replace all child records for an employee.
     */
    private function syncChildren(Employee $employee, Request $request): void
    {
        $employee->children()->delete();

        $count    = (int) $request->input('child_count', 0);
        $children = $request->input('children', []);

        for ($i = 0; $i < $count; $i++) {
            $row = $children[$i] ?? [];

            if (empty($row['first_name']) && empty($row['last_name'])) {
                continue;
            }

            $employee->children()->create([
                'first_name' => $row['first_name'] ?? null,
                'last_name'  => $row['last_name']  ?? null,
                'dob'        => $row['dob']         ?? null,
                'gender'     => $row['gender']      ?? null,
                'note'       => $row['note']        ?? null,
            ]);
        }
    }

    // ================================================================
    //  INDEX
    // ================================================================

    public function index(Request $request): View
    {
        $query = Employee::with(['unit', 'workingStatus'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name',       'like', "%{$search}%")
                  ->orWhere('officer_code',  'like', "%{$search}%")
                  ->orWhere('id_card_number','like', "%{$search}%");
            });
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('work_status_id')) {
            $query->where('work_status_id', $request->work_status_id);
        }

        $employees       = $query->paginate(20)->withQueryString();
        $units           = Unit::orderBy('name', 'asc')->get();
        $workingStatuses = WorkingStatus::orderBy('name')->get();

        return view('employees.index', compact('employees', 'units', 'workingStatuses'));
    }

    // ================================================================
    //  CREATE
    // ================================================================

    public function create(): View
    {
        $provinces       = Province::orderBy('name')->get();
        $units           = Unit::orderBy('name', 'asc')->get();
        $workingStatuses = WorkingStatus::orderBy('name')->get();

        return view('employees.create', compact('provinces', 'units', 'workingStatuses'));
    }

    // ================================================================
    //  STORE
    // ================================================================

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ((int) $request->input('child_count', 0) > 0) {
            $request->validate($this->childrenRules());
        }

        // BUG FIX #3: Remove child_count from $validated AFTER validation
        // so it does not get passed to Employee::create() — child_count is
        // NOT in the employees table; it lives only in employee_children rows.
        // We kept child_count in rules() so the raw value passes validation,
        // then we strip it here before the DB insert.
        unset($validated['child_count']);

        // Handle photo upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $validated['photo'] = $request->file('photo')
                ->store('employees/photos', 'public');
        } else {
            unset($validated['photo']);
        }

        $employee = Employee::create($validated);
        $this->syncChildren($employee, $request);

        return redirect()
            ->route('employees.index')
            ->with('success', 'ເພີ່ມຂໍ້ມູນພະນັກງານສຳເລັດ');
    }

    // ================================================================
    //  SHOW
    // ================================================================

    public function show(Employee $employee): View
    {
        $employee->load([
            'unit', 'workingStatus',
            'birthProvince', 'birthDistrict',
            'currentProvince', 'currentDistrict',
            'children',
        ]);

        return view('employees.show', compact('employee'));
    }

    // ================================================================
    //  EDIT
    // ================================================================

    public function edit(Employee $employee): View
    {
        $employee->load('children');

        $provinces       = Province::orderBy('name', 'asc')->get();
        $units           = Unit::orderBy('name', 'asc')->get();
        $workingStatuses = WorkingStatus::orderBy('name', 'asc')->get();

        return view('employees.edit', compact('employee', 'provinces', 'units', 'workingStatuses'));
    }

    // ================================================================
    //  UPDATE
    // ================================================================

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate($this->rules($employee->id));

        if ((int) $request->input('child_count', 0) > 0) {
            $request->validate($this->childrenRules());
        }

        // Strip child_count before DB update (same reason as store)
        unset($validated['child_count']);

        // BUG FIX #4: On update, when no new photo is selected the browser
        // does NOT send a 'photo' key at all, so $request->hasFile('photo')
        // is false and we correctly keep the existing photo.
        // BUT if someone submits with an empty file input, the key exists
        // but isValid() is false — we must guard against that too.
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Delete old photo from disk to avoid orphaned files
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')
                ->store('employees/photos', 'public');
        } else {
            // Keep existing photo — never overwrite with null
            unset($validated['photo']);
        }

        $employee->update($validated);
        $this->syncChildren($employee, $request);

        return redirect()
            ->route('employees.index')
            ->with('success', 'ອັບເດດຂໍ້ມູນພະນັກງານສຳເລັດ');
    }

    // ================================================================
    //  DESTROY
    // ================================================================

    public function destroy(Employee $employee): RedirectResponse
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->children()->delete();
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'ລຶບຂໍ້ມູນພະນັກງານສຳເລັດ');
    }
}
