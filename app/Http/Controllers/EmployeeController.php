<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\District;
use App\Models\Province;
use App\Models\Unit;
use App\Models\WorkingStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    // ================================================================
    //  PRIVATE HELPERS
    // ================================================================

    private function rules(?int $ignoreId = null): array
    {
        $officerCodeUnique  = Rule::unique('employees', 'officer_code');
        $idCardUnique       = Rule::unique('employees', 'id_card_number');
        $parentsNameUnique  = Rule::unique('employees', 'parents_name');
        $spouseNameUnique   = Rule::unique('employees', 'spouse_name');
        $biographyUnique    = Rule::unique('employees', 'biography');

        if ($ignoreId) {
            $officerCodeUnique->ignore($ignoreId);
            $idCardUnique->ignore($ignoreId);
            $parentsNameUnique->ignore($ignoreId);
            $spouseNameUnique->ignore($ignoreId);
            $biographyUnique->ignore($ignoreId);
        }

        return [
            'full_name'            => ['required', 'string', 'max:255'],
            'gender'               => ['nullable', 'in:male,female'],
            'dob'                  => ['nullable', 'date'],
            'officer_code'         => ['nullable', 'string', 'max:12', $officerCodeUnique],
            'id_card_number'       => ['nullable', 'string', 'max:25', $idCardUnique],
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
            'nationality'          => ['required', 'string', 'max:100'],
            'ethnicity_group'      => ['required', 'string', 'max:100'],
            'tribe'                => ['required', 'string', 'max:100'],
            'religion'             => ['required', 'string', 'max:100'],
            'class_before_1975'    => ['nullable', 'string', 'max:100'],
            'class_after_1975'     => ['nullable', 'string', 'max:100'],
            'join_revolution_date' => ['nullable', 'date'],
            'join_army_date'       => ['nullable', 'date'],
            'candidate_party_date' => ['nullable', 'date'],
            'full_party_date'      => ['nullable', 'date'],
            'current_rank_date'    => ['nullable', 'date'],
            'parents_name'         => ['required', 'string', 'max:255', $parentsNameUnique],
            'spouse_name'          => ['nullable', 'string', 'max:255', $spouseNameUnique],

            // BUG FIX #2: child_count must be included in validation rules
            // so it passes through $request->validate() without being stripped.
            'child_count'          => ['nullable', 'integer', 'min:0', 'max:20'],

            'previous_units'       => ['nullable', 'string'],
            'discipline_record'    => ['nullable', 'string'],
            'biography'            => ['nullable', 'string', $biographyUnique],
            'photo'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'biography_attachment' => ['nullable'],
        ];
    }

    private function messages(): array
    {
        return [
            'officer_code.unique'   => 'ລະຫັດນາຍທະຫານນີ້ຖືກໃຊ້ແລ້ວ, ກະລຸນາໃຊ້ລະຫັດອື່ນ.',
            'id_card_number.unique' => 'ລະຫັດບັດປະຈຳຕົວນີ້ຖືກໃຊ້ແລ້ວ, ກະລຸນາກວດສອບຄືນ.',
            'parents_name.unique'   => 'ຊື່ພໍ່ ແລະ ແມ່ນີ້ຖືກໃຊ້ແລ້ວ, ກະລຸນາກວດສອບຄືນ.',
            'spouse_name.unique'    => 'ຊື່ຄູ່ຊີວິດນີ້ຖືກໃຊ້ແລ້ວ, ກະລຸນາກວດສອບຄືນ.',
            'biography.unique'      => 'ປະຫວັດການເຄື່ອນໄຫວນີ້ຖືກໃຊ້ແລ້ວ, ກະລຸນາກວດສອບຄືນ.',
            'photo.image'          => 'ຮູບຖ່າຍຕ້ອງເປັນໄຟລ໌ຮູບພາບ.',
            'photo.mimes'          => 'ຮູບຖ່າຍຕ້ອງເປັນ JPG, PNG ຫຼື WebP.',
            'photo.max'            => 'ຮູບຖ່າຍຕ້ອງມີຂະໜາດບໍ່ເກີນ 2MB.',
            'full_name.required'    => 'ກະລຸນາປ້ອນຊື່ ແລະ ນາມສະກຸນ.',
            'unit_id.required'      => 'ກະລຸນາເລືອກກອງປະຈຳ.',
            'work_status_id.required' => 'ກະລຸນາເລືອກສະຖານະການເຮັດວຽກ.',
            'nationality.required'     => 'ກະລຸນາປ້ອນສັນຊາດ.',
            'ethnicity_group.required' => 'ກະລຸນາປ້ອນເຊື້ອຊາດ.',
            'tribe.required'           => 'ກະລຸນາປ້ອນຊົນເຜົ່າ.',
            'religion.required'        => 'ກະລຸນາປ້ອນສາດສະໜາ.',
            'parents_name.required'    => 'ກະລຸນາປ້ອນຊື່ພໍ່ແມ່.',
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
        $workingStatuses = WorkingStatus::orderBy('name','asc')->get();

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
        $provinces       = Province::orderBy('code','asc')->get();
        $units           = Unit::orderBy('name', 'asc')->get();
        $workingStatuses = WorkingStatus::orderBy('name','asc')->get();

        return view('employees.create', compact('provinces', 'units', 'workingStatuses'));
    }

    // ================================================================
    //  STORE
    // ================================================================

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), $this->messages());

        if ((int) $request->input('child_count', 0) > 0) {
            $request->validate($this->childrenRules());
        }

        unset($validated['child_count']);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        if (!empty($validated['photo'])) {
            $validated['photo'] = $validated['photo']->store('employees/photos', 'public');
        }

        if ($request->hasFile('biography_attachment') && $request->file('biography_attachment')->isValid()) {
            $request->validate(['biography_attachment' => ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx', 'max:10240']]);
            $validated['biography_attachment'] = $request->file('biography_attachment')
                ->store('employees/attachments', 'public');
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
    //  EXPORT PDF
    // ================================================================

    public function exportPdf(Employee $employee)
    {
        $employee->load([
            'unit', 'workingStatus',
            'birthProvince', 'birthDistrict',
            'currentProvince', 'currentDistrict',
            'children',
        ]);

        $photoPath = $employee->photo ? public_path('storage/' . $employee->photo) : null;
        $logoPath  = public_path('assets/images/lao-army-logo.png');
        $html = view('employees.pdf', compact('employee', 'photoPath', 'logoPath'))->render();

        $tempDir = storage_path('app/mpdf');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'default_font'  => 'phetsarath',
            'fontDir'       => [public_path('assets/font')],
            'fontdata'      => [
                'phetsarath' => [
                    'R'      => 'phetsarath_ot.ttf',
                    'B'      => 'phetsarath_ot.ttf',
                    'I'      => 'phetsarath_ot.ttf',
                    'BI'     => 'phetsarath_ot.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'tempDir'       => $tempDir,
            'margin_top'    => 7,
            'margin_bottom' => 7,
            'margin_left'   => 8,
            'margin_right'  => 8,
        ]);

        // Base64-embedded images (logo + employee photo, up to 2MB) can push
        // the HTML past the default 1MB pcre.backtrack_limit, which makes
        // mPDF's WriteHTML() throw. Raise it for this request only.
        ini_set('pcre.backtrack_limit', '10000000');

        $mpdf->SetTitle('ຂໍ້ມູນພະນັກງານ - ' . $employee->full_name);
        $mpdf->WriteHTML($html);

        $filename = 'employee_' . $employee->id . '_' . date('Ymd') . '.pdf';
        $content  = $mpdf->Output($filename, 'S');

        return response($content, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    // ================================================================
    //  EDIT
    // ================================================================

    public function edit(Employee $employee): View
    {
        $employee->load('children');

        $provinces       = Province::orderBy('code', 'asc')->get();
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
        $validated = $request->validate($this->rules($employee->id), $this->messages());

        if ((int) $request->input('child_count', 0) > 0) {
            $request->validate($this->childrenRules());
        }

        unset($validated['child_count']);

        $validated['updated_by'] = Auth::id();

        if (!empty($validated['photo'])) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $validated['photo']->store('employees/photos', 'public');
        } else {
            unset($validated['photo']);
        }

        if ($request->hasFile('biography_attachment') && $request->file('biography_attachment')->isValid()) {
            $request->validate(['biography_attachment' => ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx', 'max:10240']]);
            if ($employee->biography_attachment) {
                Storage::disk('public')->delete($employee->biography_attachment);
            }
            $validated['biography_attachment'] = $request->file('biography_attachment')
                ->store('employees/attachments', 'public');
        } else {
            unset($validated['biography_attachment']);
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

        if ($employee->biography_attachment) {
            Storage::disk('public')->delete($employee->biography_attachment);
        }

        $employee->children()->delete();
        $employee->delete(true);

        return redirect()
            ->route('employees.index')
            ->with('success', 'ລຶບຂໍ້ມູນພະນັກງານສຳເລັດ');
    }

    // ================================================================
    //  IMPORT — form
    // ================================================================

    public function importForm(): View
    {
        return view('employees.import', [
            'units'        => Unit::orderBy('name','asc')->pluck('name'),
            'workStatuses' => WorkingStatus::orderBy('name','asc')->pluck('name'),
        ]);
    }

    // ================================================================
    //  IMPORT — download blank template
    // ================================================================

    public function importTemplate()
    {
        // 38 data columns — NO '#' column so row[0] is always full_name.
        // Column letters A–AL in Excel.
        $headers = [
            'ຊື່ ແລະ ນາມສະກຸນ*',               // A  [0]
            'ເພດ (ຊາຍ/ຍິງ)',                    // B  [1]
            'ວັນເດືອນປີເກີດ (DD/MM/YYYY)',       // C  [2]
            'ລະຫັດນາຍທະຫານ',                    // D  [3]
            'ເລກບັດປະຈຳຕົວ',                    // E  [4]
            'ກອງປະຈຳ*',                          // F  [5]
            'ສະຖານະການເຮັດວຽກ*',                // G  [6]
            'ໜ້າທີ່ພັກ',                         // H  [7]
            'ໜ້າທີ່ບັນຊາ',                       // I  [8]
            'ໝູ່ເລືອດ (A/B/O/AB)',               // J  [9]
            'ບ້ານເກີດ',                           // K  [10]
            'ເມືອງ/ເຂດເກີດ',                    // L  [11]
            'ແຂວງເກີດ',                          // M  [12]
            'ບ້ານຢູ່ປັດຈຸບັນ',                  // N  [13]
            'ເມືອງ/ເຂດຢູ່ປັດຈຸບັນ',            // O  [14]
            'ແຂວງຢູ່ປັດຈຸບັນ',                 // P  [15]
            'ລະດັບວັດທະນະທຳ',                   // Q  [16]
            'ລະດັບທິດສະດີ',                     // R  [17]
            'ຮຽນທິດສະດີຈາກ',                   // S  [18]
            'ລະດັບວິຊາສະເພາະ',                  // T  [19]
            'ຮຽນວິຊາສະເພາະຈາກ',               // U  [20]
            'ສັນຊາດ',                            // V  [21]
            'ເຊື້ອຊາດ',                          // W  [22]
            'ຊົນເຜົ່າ',                          // X  [23]
            'ສາສະໜາ',                           // Y  [24]
            'ຊົນຊັ້ນກ່ອນ 1975',                // Z  [25]
            'ຊົນຊັ້ນຫຼັງ 1975',                // AA [26]
            'ວັນເຂົ້າປະຕິວັດ (DD/MM/YYYY)',    // AB [27]
            'ວັນເຂົ້າທະຫານ (DD/MM/YYYY)',      // AC [28]
            'ວັນເຂົ້າພັກສຳຮອງ (DD/MM/YYYY)',  // AD [29]
            'ວັນເຂົ້າພັກສົມບູນ (DD/MM/YYYY)', // AE [30]
            'ວັນໄດ້ຊັ້ນປັດຈຸບັນ (DD/MM/YYYY)',// AF [31]
            'ຊື່ພໍ່ແມ່',                        // AG [32]
            'ຊື່ຄູ່ຊີວິດ',                     // AH [33]
            'ຈຳນວນລູກ',                        // AI [34]
            'ກອງເກົ່າ',                        // AJ [35]
            'ບັນທຶກວິໄນ',                     // AK [36]
            'ປະຫວັດ',                          // AL [37]
        ];

        $colWidths = [
            32, 14, 26, 18, 22,    // A-E
            22, 28, 26, 26, 12,    // F-J
            18, 18, 22,            // K-M
            18, 18, 22,            // N-P
            18, 18, 25,            // Q-S
            20, 25,                // T-U
            15, 18, 15, 15,        // V-Y
            22, 22,                // Z-AA
            28, 26, 28, 28, 28,    // AB-AF
            26, 26, 10,            // AG-AI
            30, 26, 40,            // AJ-AL
        ];

        $example = [
            'ທ່ານ ທົດສອບ ຕົວຢ່າງ',      // A  ← ລຶບແຖວນີ້ ແລ້ວໃສ່ຂໍ້ມູນຕົວຈິງ
            'ຊາຍ',                         // B  ຊາຍ ຫຼື ຍິງ
            '01/01/1990',                  // C  DD/MM/YYYY
            'CODE001',                     // D
            '1234567890123',               // E
            '(ໃສ່ຊື່ກອງທີ່ຖືກຕ້ອງ)',       // F  *** ຕ້ອງຕົງກັບຊື່ກອງໃນລະບົບ ***
            'ກຳລັງປະຈຳການຢູ່',            // G  *** ຕ້ອງຕົງກັບຊື່ສະຖານະໃນລະບົບ ***
            'ຄ.ກ.ມ',                      // H
            'ຜູ້ບັນຊາ',                   // I
            'A',                           // J  A, B, O, ຫຼື AB
            'ບ້ານ ໂນນສູງ',                // K
            'ຈັນທະບູລີ',                  // L  ຊື່ເມືອງ/ເຂດ
            'ນະຄອນຫຼວງວຽງຈັນ',           // M  ຊື່ແຂວງ
            'ບ້ານ ສີໂຄດ',                // N
            'ໄຊທານີ',                     // O
            'ນະຄອນຫຼວງວຽງຈັນ',           // P
            'ມັດທະຍົມ',                   // Q
            'ຊັ້ນກາງ',                    // R
            'ໂຮງຮຽນການເມືອງ',            // S
            'ປະລິນຍາຕີ',                  // T
            'ມ.ສ.ກ.',                      // U
            'ລາວ',                         // V
            'ລາວ',                         // W
            'ລາວລຸ່ມ',                    // X
            'ພຸດທະສາດ',                   // Y
            'ຊາວໄຮ່',                     // Z
            'ກຳມາກອນ',                   // AA
            '01/01/1975',                 // AB
            '01/06/1980',                 // AC
            '01/01/2000',                 // AD
            '01/06/2001',                 // AE
            '15/03/2015',                 // AF
            'ພໍ່: ທ່ານ A  ແມ່: ທ່ານ ນາງ B', // AG
            'ທ່ານ ນາງ ຄູ່ຊີວິດ',         // AH
            '2',                          // AI
            'ກອງເກົ່າ 1, ກອງເກົ່າ 2',    // AJ
            'ບໍ່ມີ',                      // AK
            'ຊົງຊາດລາວ ດຳລົງຊີວິດ...',   // AL
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template');

        $colCount = count($headers);
        $lastCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);

        // Set all data columns (rows 2 onward) as TEXT format to prevent
        // Excel from converting officer codes / ID numbers to scientific notation
        // and to keep leading zeros in numeric strings.
        $textFormat = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT;
        foreach ($headers as $i => $header) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue($col . '1', $header);
            // Apply text format to the entire data column (rows 2–1000)
            $sheet->getStyle("{$col}2:{$col}1000")
                ->getNumberFormat()
                ->setFormatCode($textFormat);
        }

        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11, 'name' => 'Calibri'],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF312E81']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Write example row — all cells as explicit text to preserve leading zeros
        foreach ($example as $i => $val) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet->getCell($col . '2')->setValueExplicit(
                (string) $val,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
        }

        // Style example row: italic gray + yellow background to signal "delete this row"
        $sheet->getStyle("A2:{$lastCol}2")->applyFromArray([
            'font' => ['italic' => true, 'color' => ['argb' => 'FF64748b']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFF9C4']],
        ]);

        foreach ($colWidths as $i => $w) {
            $sheet->getColumnDimension(
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1)
            )->setWidth($w);
        }

        $sheet->freezePane('A2');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(
            fn() => $writer->save('php://output'),
            'import_template_employees.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    // ================================================================
    //  IMPORT — parse uploaded file → JSON preview
    // ================================================================

    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xls,xlsx', 'max:10240'],
        ]);

        try {
            $file = $request->file('file');
            $ext  = strtolower($file->getClientOriginalExtension());

            if ($ext === 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                $reader->setDelimiter(',');
                $reader->setEnclosure('"');
            } elseif ($ext === 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($file->getPathname());
            $allRows     = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

            if (empty($allRows)) {
                return response()->json(['success' => false, 'message' => 'ໄຟລ໌ຫວ່າງເປົ່າ']);
            }

            $headers = array_shift($allRows);

            $rows = array_values(array_filter($allRows, function ($row) {
                return count(array_filter($row, fn($v) => $v !== null && $v !== '')) > 0;
            }));

            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'ບໍ່ພົບຂໍ້ມູນໃນໄຟລ໌ (ມີແຕ່ header)']);
            }

            return response()->json([
                'success' => true,
                'headers' => $headers,
                'rows'    => $rows,
                'count'   => count($rows),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'ອ່ານໄຟລ໌ບໍ່ສຳເລັດ: ' . $e->getMessage()]);
        }
    }

    // ================================================================
    //  IMPORT — persist rows → employees table
    // ================================================================

    public function importSubmit(Request $request)
    {
        $request->validate([
            'rows'    => ['required', 'array', 'min:1'],
            'headers' => ['required', 'array'],
        ]);

        $imported = 0;
        $skipped  = [];

        // Caches to avoid repeated DB queries for same name
        $unitCache   = [];
        $statusCache = [];
        $provCache   = [];
        $distCache   = [];

        $parseDate = function ($raw): ?string {
            $raw = trim((string) ($raw ?? ''));
            if ($raw === '') return null;
            if (is_numeric($raw)) {
                try {
                    return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $raw)->format('Y-m-d');
                } catch (\Exception) { return null; }
            }
            foreach (['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y', 'd/m/y'] as $fmt) {
                $dt = \DateTime::createFromFormat($fmt, $raw);
                if ($dt && $dt->format($fmt) === $raw) return $dt->format('Y-m-d');
                if ($dt) return $dt->format('Y-m-d');
            }
            return null;
        };

        // DB-based lookup: MySQL collation for encoding-safe Lao character matching,
        // with PHP-based contains fallback. Results cached per model.
        $dbLookup = function (string $model, array &$cache, $value): ?int {
            $value = trim((string) ($value ?? ''));
            if ($value === '') return null;
            if (array_key_exists($value, $cache)) return $cache[$value];

            // 1. Exact match via DB collation
            $found = $model::where('name', $value)->first(['id', 'name']);
            // 2. Case-insensitive LOWER() match
            if (!$found) {
                $found = $model::whereRaw('LOWER(name) = LOWER(?)', [$value])->first(['id', 'name']);
            }
            // 3. PHP mb_strtolower + str_contains (handles partial/abbrev. differences)
            if (!$found) {
                $lv = mb_strtolower($value);
                foreach ($model::all(['id', 'name']) as $r) {
                    $ln = mb_strtolower((string) $r->name);
                    if ($ln === $lv || str_contains($ln, $lv) || str_contains($lv, $ln)) {
                        $found = $r;
                        break;
                    }
                }
            }

            $cache[$value] = $found ? (int) $found->id : null;
            return $cache[$value];
        };

        $str = fn($v) => (preg_replace('/\s+/u', ' ', trim((string) ($v ?? ''))) ?: null);

        // Build column-index map by searching header names (case-insensitive, partial match).
        // This works regardless of column order or template version (38-col, 39-col, custom).
        $rawHeaders = array_map(
            fn($h) => mb_strtolower(trim((string) ($h ?? ''))),
            $request->input('headers', [])
        );

        $findCol = function (string ...$keywords) use ($rawHeaders): int {
            foreach ($rawHeaders as $i => $h) {
                foreach ($keywords as $kw) {
                    if ($kw !== '' && str_contains($h, mb_strtolower($kw))) return $i;
                }
            }
            return -1;
        };

        $C = [
            'full_name'   => $findCol('ຊື່ ແລະ ນາມສະກຸນ'),
            'gender'      => $findCol('ເພດ'),
            'dob'         => $findCol('ວັນເດືອນປີເກີດ', 'ວັນເກີດ'),
            'officer'     => $findCol('ລະຫັດນາຍທະຫານ'),
            'id_card'     => $findCol('ເລກບັດ'),
            'unit'        => $findCol('ກອງປະຈຳ'),
            'status'      => $findCol('ສະຖານະ'),
            'party_duty'  => $findCol('ໜ້າທີ່ພັກ'),
            'command'     => $findCol('ໜ້າທີ່ບັນຊາ'),
            'blood'       => $findCol('ໝູ່ເລືອດ'),
            'bvill'       => $findCol('ບ້ານເກີດ'),
            'bdist'       => $findCol('ເມືອງ/ເຂດເກີດ', 'ເມືອງເກີດ'),
            'bprov'       => $findCol('ແຂວງເກີດ'),
            'cvill'       => $findCol('ບ້ານຢູ່ປັດຈຸບັນ', 'ບ້ານຢູ່'),
            'cdist'       => $findCol('ເມືອງ/ເຂດຢູ່', 'ເມືອງຢູ່ປັດຈຸບັນ', 'ເມືອງຢູ່'),
            'cprov'       => $findCol('ແຂວງຢູ່ປັດຈຸບັນ', 'ແຂວງຢູ່'),
            'culture'     => $findCol('ວັດທະນະທຳ'),
            'theory_lv'   => $findCol('ລະດັບທິດສະດີ'),
            'theory_from' => $findCol('ຮຽນທິດສະດີ'),
            'prof_lv'     => $findCol('ລະດັບວິຊາສະເພາະ'),
            'prof_from'   => $findCol('ຮຽນວິຊາສະເພາະ', 'ຮຽນວິຊາ'),
            'nat'         => $findCol('ສັນຊາດ'),
            'eth'         => $findCol('ເຊື້ອຊາດ'),
            'tribe'       => $findCol('ຊົນເຜົ່າ'),
            'religion'    => $findCol('ສາສະໜາ'),
            'cl_before'   => $findCol('ຊົນຊັ້ນກ່ອນ', 'ກ່ອນ 1975'),
            'cl_after'    => $findCol('ຊົນຊັ້ນຫຼັງ', 'ຫຼັງ 1975'),
            'j_rev'       => $findCol('ເຂົ້າປະຕິວັດ', 'ປະຕິວັດ'),
            'j_army'      => $findCol('ເຂົ້າທະຫານ'),
            'j_cand'      => $findCol('ພັກສຳຮອງ', 'ສຳຮອງ'),
            'j_full'      => $findCol('ພັກສົມບູນ', 'ສົມບູນ'),
            'rank_date'   => $findCol('ໄດ້ຊັ້ນ', 'ຊັ້ນປັດຈຸບັນ'),
            'parents'     => $findCol('ພໍ່ແມ່'),
            'spouse'      => $findCol('ຄູ່ຊີວິດ'),
            'children'    => $findCol('ຈຳນວນລູກ', 'ຈ.ລູກ'),
            'prev_units'  => $findCol('ກອງເກົ່າ'),
            'discipline'  => $findCol('ບັນທຶກວິໄນ', 'ວິໄນ'),
            'biography'   => $findCol('ປະຫວັດ'),
        ];

        // Require at minimum the three essential columns
        if ($C['full_name'] < 0 || $C['unit'] < 0 || $C['status'] < 0) {
            $missing = [];
            if ($C['full_name'] < 0) $missing[] = 'ຊື່ ແລະ ນາມສະກຸນ';
            if ($C['unit'] < 0)      $missing[] = 'ກອງປະຈຳ';
            if ($C['status'] < 0)    $missing[] = 'ສະຖານະ';
            return response()->json([
                'success' => false,
                'message' => 'ໄຟລ໌ຂາດ header: ' . implode(', ', $missing)
                           . ' — ດາວໂຫຼດ Template ໃໝ່ ຫຼື ກວດ header ແຖວທຳອິດ',
            ]);
        }

        $get = fn(array $row, string $key) => ($C[$key] >= 0) ? ($row[$C[$key]] ?? null) : null;

        DB::beginTransaction();
        try {
            foreach ($request->input('rows') as $i => $row) {
                $rowNum = $i + 2;

                $fullName = $str($get($row, 'full_name'));
                if (!$fullName) {
                    $skipped[] = "ແຖວ {$rowNum}: ຊື່ ແລະ ນາມສະກຸນ ຫວ່າງ — ຂ້າມ";
                    continue;
                }

                $genderRaw = mb_strtolower(trim((string) ($get($row, 'gender') ?? '')));
                $gender    = match(true) {
                    in_array($genderRaw, ['ຊາຍ', 'male', 'm'])   => 'male',
                    in_array($genderRaw, ['ຍິງ', 'female', 'f']) => 'female',
                    default => null,
                };

                $unitId = $dbLookup(Unit::class, $unitCache, $get($row, 'unit'));
                if (!$unitId) {
                    $skipped[] = "ແຖວ {$rowNum} ({$fullName}): ກອງ '" . $str($get($row, 'unit')) . "' ບໍ່ພົບ — ກວດຊື່ໃຫ້ຕົງກັບໃນລະບົບ";
                    continue;
                }

                $statusId = $dbLookup(WorkingStatus::class, $statusCache, $get($row, 'status'));
                if (!$statusId) {
                    $skipped[] = "ແຖວ {$rowNum} ({$fullName}): ສະຖານະ '" . $str($get($row, 'status')) . "' ບໍ່ພົບ — ກວດຊື່ໃຫ້ຕົງກັບໃນລະບົບ";
                    continue;
                }

                $bloodGroup = strtoupper(trim((string) ($get($row, 'blood') ?? '')));
                if (!in_array($bloodGroup, ['A', 'B', 'O', 'AB'])) $bloodGroup = null;

                Employee::create([
                    'full_name'            => $fullName,
                    'gender'               => $gender,
                    'dob'                  => $parseDate($get($row, 'dob')),
                    'officer_code'         => substr($str($get($row, 'officer')) ?? '', 0, 12) ?: null,
                    'id_card_number'       => substr($str($get($row, 'id_card')) ?? '', 0, 25) ?: null,
                    'unit_id'              => $unitId,
                    'work_status_id'       => $statusId,
                    'party_duty'           => $str($get($row, 'party_duty')),
                    'command_duty'         => $str($get($row, 'command')),
                    'blood_group'          => $bloodGroup,
                    'birth_village'        => $str($get($row, 'bvill')),
                    'birth_district_id'    => $dbLookup(District::class, $distCache, $get($row, 'bdist')),
                    'birth_province_id'    => $dbLookup(Province::class, $provCache, $get($row, 'bprov')),
                    'current_village'      => $str($get($row, 'cvill')),
                    'current_district_id'  => $dbLookup(District::class, $distCache, $get($row, 'cdist')),
                    'current_province_id'  => $dbLookup(Province::class, $provCache, $get($row, 'cprov')),
                    'culture_level'        => $str($get($row, 'culture')),
                    'theory_level'         => $str($get($row, 'theory_lv')),
                    'theory_from'          => $str($get($row, 'theory_from')),
                    'profession_level'     => $str($get($row, 'prof_lv')),
                    'profession_from'      => $str($get($row, 'prof_from')),
                    'nationality'          => $str($get($row, 'nat')),
                    'ethnicity_group'      => $str($get($row, 'eth')),
                    'tribe'                => $str($get($row, 'tribe')),
                    'religion'             => $str($get($row, 'religion')),
                    'class_before_1975'    => $str($get($row, 'cl_before')),
                    'class_after_1975'     => $str($get($row, 'cl_after')),
                    'join_revolution_date' => $parseDate($get($row, 'j_rev')),
                    'join_army_date'       => $parseDate($get($row, 'j_army')),
                    'candidate_party_date' => $parseDate($get($row, 'j_cand')),
                    'full_party_date'      => $parseDate($get($row, 'j_full')),
                    'current_rank_date'    => $parseDate($get($row, 'rank_date')),
                    'parents_name'         => $str($get($row, 'parents')),
                    'spouse_name'          => $str($get($row, 'spouse')),
                    'child_count'          => is_numeric($get($row, 'children') ?? '') ? (int) $get($row, 'children') : 0,
                    'previous_units'       => $str($get($row, 'prev_units')),
                    'discipline_record'    => $str($get($row, 'discipline')),
                    'biography'            => $str($get($row, 'biography')),
                    'created_by'           => Auth::id(),
                    'updated_by'           => Auth::id(),
                ]);
                $imported++;
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'imported' => $imported,
                'skipped'  => $skipped,
                'message'  => "ນຳເຂົ້າສຳເລັດ {$imported} ລາຍການ" . (count($skipped) ? ' · ຂ້າມ ' . count($skipped) . ' ລາຍການ' : ''),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage()]);
        }
    }
}
