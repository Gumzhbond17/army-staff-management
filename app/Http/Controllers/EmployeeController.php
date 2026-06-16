<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Province;
use App\Models\Unit;
use App\Models\WorkingStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // ================================================================
    //  PRIVATE HELPERS
    // ================================================================

    private function rules(): array
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
        $query           = $this->buildExportQuery($request);
        $employees       = $query->paginate(20)->withQueryString();
        $units           = Unit::orderBy('name', 'asc')->get();
        $workingStatuses = WorkingStatus::orderBy('name')->get();

        return view('employees.index', compact('employees', 'units', 'workingStatuses'));
    }

    // ================================================================
    //  EXPORT HELPERS
    // ================================================================

    private function buildExportQuery(Request $request)
    {
        $query = Employee::with(['unit', 'workingStatus'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name',        'like', "%{$search}%")
                  ->orWhere('officer_code',   'like', "%{$search}%")
                  ->orWhere('id_card_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('work_status_id')) {
            $query->where('work_status_id', $request->work_status_id);
        }

        return $query;
    }

    // All 39 employee column headers
    private static function exportColumnHeaders(): array
    {
        return [
            '#', 'ຊື່ ແລະ ນາມສະກຸນ', 'ເພດ', 'ວັນເດືອນປີເກີດ',
            'ລະຫັດນາຍທະຫານ', 'ເລກບັດປະຈຳຕົວ',
            'ກອງປະຈຳ', 'ສະຖານະ', 'ໜ້າທີ່ພັກ', 'ໜ້າທີ່ບັນຊາ',
            'ໝູ່ເລືອດ',
            'ບ້ານເກີດ', 'ເມືອງເກີດ', 'ແຂວງເກີດ',
            'ບ້ານຢູ່ປັດຈຸບັນ', 'ເມືອງຢູ່ປັດຈຸບັນ', 'ແຂວງຢູ່ປັດຈຸບັນ',
            'ລະດັບວັດທະນະທຳ', 'ລະດັບທິດສະດີ', 'ຮຽນທິດສະດີຈາກ',
            'ລະດັບວິຊາສະເພາະ', 'ຮຽນວິຊາສະເພາະຈາກ',
            'ສັນຊາດ', 'ເຊື້ອຊາດ', 'ຊົນເຜົ່າ', 'ສາສະໜາ',
            'ຊົນຊັ້ນກ່ອນ 1975', 'ຊົນຊັ້ນຫຼັງ 1975',
            'ວັນເຂົ້າປະຕິວັດ', 'ວັນເຂົ້າທະຫານ',
            'ວັນເຂົ້າພັກສຳຮອງ', 'ວັນເຂົ້າພັກສົມບູນ', 'ວັນໄດ້ຊັ້ນປັດຈຸບັນ',
            'ຊື່ພໍ່ແມ່', 'ຊື່ຄູ່ຊີວິດ', 'ຈຳນວນລູກ',
            'ກອງເກົ່າ', 'ບັນທຶກວິໄນ', 'ປະຫວັດ',
        ];
    }

    // All 39 employee field values (indices 0-38 matching headers above)
    private static function exportRowData(Employee $e, int $index): array
    {
        return [
            $index,                                                                         // 0
            $e->full_name ?? '',                                                            // 1
            $e->gender === 'male' ? 'ຊາຍ' : ($e->gender === 'female' ? 'ຍິງ' : ''),       // 2
            $e->dob ? $e->dob->format('d/m/Y') : '',                                       // 3
            $e->officer_code ?? '',                                                         // 4  → string type
            $e->id_card_number ?? '',                                                       // 5  → string type
            $e->unit?->name ?? '',                                                          // 6
            $e->workingStatus?->name ?? '',                                                 // 7
            $e->party_duty ?? '',                                                           // 8
            $e->command_duty ?? '',                                                         // 9
            $e->blood_group ?? '',                                                          // 10
            $e->birth_village ?? '',                                                        // 11
            $e->birthDistrict?->name ?? '',                                                 // 12
            $e->birthProvince?->name ?? '',                                                 // 13
            $e->current_village ?? '',                                                      // 14
            $e->currentDistrict?->name ?? '',                                               // 15
            $e->currentProvince?->name ?? '',                                               // 16
            $e->culture_level ?? '',                                                        // 17
            $e->theory_level ?? '',                                                         // 18
            $e->theory_from ?? '',                                                          // 19
            $e->profession_level ?? '',                                                     // 20
            $e->profession_from ?? '',                                                      // 21
            $e->nationality ?? '',                                                          // 22
            $e->ethnicity_group ?? '',                                                      // 23
            $e->tribe ?? '',                                                                // 24
            $e->religion ?? '',                                                             // 25
            $e->class_before_1975 ?? '',                                                    // 26
            $e->class_after_1975 ?? '',                                                     // 27
            $e->join_revolution_date ? $e->join_revolution_date->format('d/m/Y') : '',     // 28
            $e->join_army_date ? $e->join_army_date->format('d/m/Y') : '',                 // 29
            $e->candidate_party_date ? $e->candidate_party_date->format('d/m/Y') : '',     // 30
            $e->full_party_date ? $e->full_party_date->format('d/m/Y') : '',               // 31
            $e->current_rank_date ? $e->current_rank_date->format('d/m/Y') : '',           // 32
            $e->parents_name ?? '',                                                         // 33
            $e->spouse_name ?? '',                                                          // 34
            $e->child_count ?? 0,                                                           // 35
            $e->previous_units ?? '',                                                       // 36
            $e->discipline_record ?? '',                                                    // 37
            $e->biography ?? '',                                                            // 38
        ];
    }

    // Children sheet headers
    private static function exportChildHeaders(): array
    {
        return ['#', 'ລໍາດັບພະນັກງານ', 'ຊື່ພະນັກງານ', 'ຊື່', 'ນາມສະກຸນ', 'ວັນເດືອນປີເກີດ', 'ເພດ', 'ໝາຍເຫດ'];
    }

    // Children sheet row values
    private static function exportChildRow(\App\Models\EmployeeChild $c, int $index, string $empName): array
    {
        return [
            $index,
            $c->employee_id,
            $empName,
            $c->first_name ?? '',
            $c->last_name ?? '',
            $c->dob ? $c->dob->format('d/m/Y') : '',
            $c->gender === 'male' ? 'ຊາຍ' : ($c->gender === 'female' ? 'ຍິງ' : $c->gender ?? ''),
            $c->note ?? '',
        ];
    }

    // ================================================================
    //  EXPORT CSV (all employee fields, one row per employee)
    // ================================================================

    public function exportCsv(Request $request)
    {
        $employees = $this->buildExportQuery($request)
            ->with(['birthProvince', 'birthDistrict', 'currentProvince', 'currentDistrict'])
            ->get();

        $filename = 'employees_' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($employees) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
            fputcsv($out, self::exportColumnHeaders());

            foreach ($employees as $i => $e) {
                fputcsv($out, self::exportRowData($e, $i + 1));
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // ================================================================
    //  EXPORT EXCEL (.xlsx — Sheet 1: employees, Sheet 2: children)
    // ================================================================

    public function exportExcel(Request $request)
    {
        $employees = $this->buildExportQuery($request)
            ->with(['birthProvince', 'birthDistrict', 'currentProvince', 'currentDistrict', 'children'])
            ->get();

        $filename = 'employees_' . now()->format('Y-m-d') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $headerStyle = [
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFFFFFF'], 'name' => 'Calibri'],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF312E81']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FF312E81']]],
        ];

        $dataStyle = [
            'font'      => ['name' => 'Calibri', 'size' => 11],
            'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFE5E7EB']]],
        ];

        // ── Sheet 1: All employee fields ─────────────────────────────
        $empSheet = $spreadsheet->getActiveSheet();
        $empSheet->setTitle('ຂໍ້ມູນພະນັກງານ');

        $empHeaders  = self::exportColumnHeaders();
        $empColCount = count($empHeaders);
        $lastEmpCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($empColCount);

        foreach ($empHeaders as $i => $label) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $empSheet->setCellValue($col . '1', $label);
        }
        $empSheet->getStyle('A1:' . $lastEmpCol . '1')->applyFromArray($headerStyle);
        $empSheet->getRowDimension(1)->setRowHeight(30);

        // 39 column widths matching exportColumnHeaders order
        $empColWidths = [
            5,  30, 8,  14, 18, 20,     // #, name, gender, dob, officer_code, id_card
            22, 15, 25, 25, 10,         // unit, status, party, command, blood
            18, 18, 18,                 // birth_village, birth_district, birth_province
            18, 18, 18,                 // cur_village, cur_district, cur_province
            18, 18, 22,                 // culture, theory, theory_from
            20, 22,                     // profession, profession_from
            15, 18, 15, 15,             // nationality, ethnicity, tribe, religion
            20, 20,                     // class_before, class_after
            20, 18, 22, 20, 22,         // 5 date columns (28-32)
            25, 25, 10,                 // parents, spouse, child_count
            30, 25, 40,                 // previous_units, discipline, biography
        ];
        foreach ($empColWidths as $i => $w) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $empSheet->getColumnDimension($col)->setWidth($w);
        }

        // Force these 0-based columns as TYPE_STRING to prevent scientific notation
        $empStringCols = [4, 5]; // officer_code, id_card_number

        // 0-based columns to centre: #, gender, dob, blood, 5 date cols, child_count
        $empCentredCols = [0, 2, 3, 10, 28, 29, 30, 31, 32, 35];

        foreach ($employees as $rowIdx => $e) {
            $excelRow = $rowIdx + 2;
            $rowData  = self::exportRowData($e, $rowIdx + 1);

            foreach ($rowData as $colIdx => $val) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1);
                $cellRef   = $colLetter . $excelRow;

                if (in_array($colIdx, $empStringCols, true)) {
                    $empSheet->getCell($cellRef)->setValueExplicit(
                        (string) $val,
                        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                    );
                } else {
                    $empSheet->setCellValue($cellRef, $val);
                }
            }

            $empSheet->getStyle('A' . $excelRow . ':' . $lastEmpCol . $excelRow)->applyFromArray($dataStyle);

            foreach ($empCentredCols as $c) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c + 1);
                $empSheet->getStyle($col . $excelRow)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

            $empSheet->getRowDimension($excelRow)->setRowHeight(22);
        }

        $empSheet->freezePane('A2');
        $empSheet->setAutoFilter('A1:' . $lastEmpCol . '1');

        // ── Sheet 2: Children ─────────────────────────────────────────
        $childSheet = $spreadsheet->createSheet();
        $childSheet->setTitle('ຂໍ້ມູນລູກ');

        $childHeaders  = self::exportChildHeaders();
        $childColCount = count($childHeaders);
        $lastChildCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($childColCount);

        foreach ($childHeaders as $i => $label) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $childSheet->setCellValue($col . '1', $label);
        }
        $childSheet->getStyle('A1:' . $lastChildCol . '1')->applyFromArray($headerStyle);
        $childSheet->getRowDimension(1)->setRowHeight(28);

        $childColWidths = [5, 15, 30, 20, 20, 14, 10, 25];
        foreach ($childColWidths as $i => $w) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $childSheet->getColumnDimension($col)->setWidth($w);
        }

        // 0-based columns to centre: #, employee_id, dob, gender
        $childCentredCols = [0, 1, 5, 6];
        $childRow = 2;
        $childNum = 1;

        foreach ($employees as $e) {
            foreach ($e->children as $child) {
                $rowData = self::exportChildRow($child, $childNum, $e->full_name ?? '');

                foreach ($rowData as $colIdx => $val) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1);
                    $childSheet->setCellValue($col . $childRow, $val);
                }

                $childSheet->getStyle('A' . $childRow . ':' . $lastChildCol . $childRow)->applyFromArray($dataStyle);

                foreach ($childCentredCols as $c) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c + 1);
                    $childSheet->getStyle($col . $childRow)
                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }

                $childSheet->getRowDimension($childRow)->setRowHeight(22);
                $childRow++;
                $childNum++;
            }
        }

        $childSheet->freezePane('A2');
        $childSheet->setAutoFilter('A1:' . $lastChildCol . '1');

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(
            fn () => $writer->save('php://output'),
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
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

        unset($validated['child_count']);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $request->validate(['photo' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);
            $validated['photo'] = $request->file('photo')
                ->store('employees/photos', 'public');
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

        $employeeChildren = $employee->children->map(function ($c) {
            $dob = $c->getRawOriginal('dob');
            return [
                'first_name' => $c->first_name,
                'last_name'  => $c->last_name,
                'dob'        => $dob ?: null,
                'gender'     => $c->gender,
                'note'       => $c->note,
            ];
        })->values()->toArray();

        return view('employees.edit_form', compact('employee', 'provinces', 'units', 'workingStatuses', 'employeeChildren'));
    }

    // ================================================================
    //  UPDATE
    // ================================================================

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ((int) $request->input('child_count', 0) > 0) {
            $request->validate($this->childrenRules());
        }

        unset($validated['child_count']);

        $validated['updated_by'] = Auth::id();

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $request->validate(['photo' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')
                ->store('employees/photos', 'public');
        } else {
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
