@extends('layouts.main')

@section('content')
<div class="py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">ຂໍ້ມູນພະນັກງານ</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> ແກ້ໄຂ
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> ກັບຄືນ
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ໝວດ I --}}
    <div class="card mb-3">
        <div class="card-header fw-semibold">ໝວດ I · ຂໍ້ມູນທົ່ວໄປ</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="text-muted small">ເພດ</div>
                    <div>{{ $employee->gender ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ຊື່ ແລະ ນາມສະກຸນ</div>
                    <div>{{ $employee->full_name }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ໜ່ວຍງານ</div>
                    <div>{{ $employee->unit->name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ວັນເດືອນປີເກີດ</div>
                    <div>{{ $employee->dob ? \Carbon\Carbon::parse($employee->dob)->format('d/m/Y') : '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ໜ້າທີ່ພັກ</div>
                    <div>{{ $employee->party_duty ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ໜ້າທີ່ບັນຊາ</div>
                    <div>{{ $employee->command_duty ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ລະຫັດນາຍທະຫານ</div>
                    <div>{{ $employee->officer_code ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ເລກບັດປະຈຳຕົວ</div>
                    <div>{{ $employee->id_card_number ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ສະຖານະການເຮັດວຽກ</div>
                    <div>{{ $employee->workStatus->name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ໝູ່ເລືອດ</div>
                    <div>{{ $employee->blood_group ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ໝວດ II --}}
    <div class="card mb-3">
        <div class="card-header fw-semibold">ໝວດ II · ສະຖານທີ່ເກີດ</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">ແຂວງ</div>
                    <div>{{ $employee->birthProvince->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ເມືອງ</div>
                    <div>{{ $employee->birthDistrict->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ບ້ານ</div>
                    <div>{{ $employee->birth_village ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ໝວດ III --}}
    <div class="card mb-3">
        <div class="card-header fw-semibold">ໝວດ III · ທີ່ຢູ່ປັດຈຸບັນ</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">ແຂວງ</div>
                    <div>{{ $employee->currentProvince->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ເມືອງ</div>
                    <div>{{ $employee->currentDistrict->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ບ້ານ</div>
                    <div>{{ $employee->current_village ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ໝວດ IV --}}
    <div class="card mb-3">
        <div class="card-header fw-semibold">ໝວດ IV · ການສຶກສາ</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">ລະດັບວັດທະນະທຳ</div>
                    <div>{{ $employee->culture_level ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ລະດັບທິດສະດີ</div>
                    <div>{{ $employee->theory_level ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ຈາກສະຖາບັນ</div>
                    <div>{{ $employee->theory_from ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ລະດັບວິຊາຊີບ</div>
                    <div>{{ $employee->profession_level ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ຈາກສະຖາບັນ</div>
                    <div>{{ $employee->profession_from ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ໝວດ V --}}
    <div class="card mb-3">
        <div class="card-header fw-semibold">ໝວດ V · ຊົນເຜົ່າ ແລະ ສາສະໜາ</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="text-muted small">ສັນຊາດ</div>
                    <div>{{ $employee->nationality ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ກຸ່ມຊົນເຜົ່າ</div>
                    <div>{{ $employee->ethnicity_group ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ເຜົ່າ</div>
                    <div>{{ $employee->tribe ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ສາສະໜາ</div>
                    <div>{{ $employee->religion ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ຊັ້ນກ່ອນ 1975</div>
                    <div>{{ $employee->class_before_1975 ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">ຊັ້ນຫຼັງ 1975</div>
                    <div>{{ $employee->class_after_1975 ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ໝວດ VI --}}
    <div class="card mb-3">
        <div class="card-header fw-semibold">ໝວດ VI · ວັນທີສຳຄັນ</div>
        <div class="card-body">
            <div class="row g-3">
                @foreach([
                    'join_revolution_date' => 'ວັນເຂົ້າຮ່ວມປະຕິວັດ',
                    'join_army_date'       => 'ວັນເຂົ້າກອງທັບ',
                    'candidate_party_date' => 'ວັນເປັນສະມາຊິກທົດລອງ',
                    'full_party_date'      => 'ວັນເປັນສະມາຊິກເຕັມ',
                    'current_rank_date'    => 'ວັນໄດ້ຍົດປັດຈຸບັນ',
                ] as $field => $label)
                <div class="col-md-4">
                    <div class="text-muted small">{{ $label }}</div>
                    <div>{{ $employee->$field ? \Carbon\Carbon::parse($employee->$field)->format('d/m/Y') : '-' }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ໝວດ VII --}}
    <div class="card mb-3">
        <div class="card-header fw-semibold">ໝວດ VII · ຖອດຖື ແລະ ວິໄນ</div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="text-muted small">ຊື່ພໍ່ແມ່</div>
                    <div>{{ $employee->parents_name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ຊື່ຄູ່ສົມລົດ</div>
                    <div>{{ $employee->spouse_name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">ຈຳນວນລູກ</div>
                    <div>{{ $employee->child_count ?? 0 }} ຄົນ</div>
                </div>
            </div>

            {{-- Children table --}}
            @if($employee->children && $employee->children->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>ຊື່</th>
                            <th>ນາມສະກຸນ</th>
                            <th>ວັນເດືອນປີເກີດ</th>
                            <th>ເພດ</th>
                            <th>ໝາຍເຫດ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee->children as $i => $child)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $child->first_name }}</td>
                            <td>{{ $child->last_name }}</td>
                            <td>{{ $child->dob ? \Carbon\Carbon::parse($child->dob)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $child->gender ?? '-' }}</td>
                            <td>{{ $child->note ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="text-muted small">ໜ່ວຍງານທີ່ຜ່ານມາ</div>
                    <div style="white-space: pre-line">{{ $employee->previous_units ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">ປະຫວັດວິໄນ</div>
                    <div style="white-space: pre-line">{{ $employee->discipline_record ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ໝວດ VIII --}}
    <div class="card mb-3">
        <div class="card-header fw-semibold">ໝວດ VIII · ຊີວະປະຫວັດ</div>
        <div class="card-body">
            <div style="white-space: pre-line">{{ $employee->biography ?? '-' }}</div>
        </div>
    </div>

</div>
@endsection
