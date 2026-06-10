@extends('layouts.main')

@push('styles')
<style>
    .section-card { border-radius: 12px; margin-bottom: 1.25rem; }
    .section-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
</style>
@endpush

@section('content')
<div class="py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">ແກ້ໄຂຂໍ້ມູນພະນັກງານ</h4>
        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> ກັບຄືນ
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- ໝວດ I --}}
        <div class="card section-card">
            <div class="card-header d-flex align-items-center gap-2 fw-semibold">
                <div class="section-icon bg-primary bg-opacity-10 text-primary">I</div>
                ໝວດ I · ຂໍ້ມູນທົ່ວໄປ
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">ເພດ</label>
                        <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                            <option value="">-- ເລືອກ --</option>
                            <option value="ຊາຍ" {{ old('gender', $employee->gender) == 'ຊາຍ' ? 'selected' : '' }}>ຊາຍ</option>
                            <option value="ຍິງ" {{ old('gender', $employee->gender) == 'ຍິງ' ? 'selected' : '' }}>ຍິງ</option>
                        </select>
                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ຊື່ ແລະ ນາມສະກຸນ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                               name="full_name" value="{{ old('full_name', $employee->full_name) }}">
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">ໜ່ວຍງານ <span class="text-danger">*</span></label>
                        <select class="form-select select2 @error('unit_id') is-invalid @enderror" name="unit_id">
                            <option value="">-- ເລືອກໜ່ວຍງານ --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $employee->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">ວັນເດືອນປີເກີດ</label>
                        <input type="date" class="form-control @error('dob') is-invalid @enderror"
                               name="dob" value="{{ old('dob', $employee->dob) }}">
                        @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">ໜ້າທີ່ພັກ</label>
                        <input type="text" class="form-control" name="party_duty"
                               value="{{ old('party_duty', $employee->party_duty) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">ໜ້າທີ່ບັນຊາ</label>
                        <input type="text" class="form-control" name="command_duty"
                               value="{{ old('command_duty', $employee->command_duty) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ລະຫັດນາຍທະຫານ</label>
                        <input type="text" class="form-control" name="officer_code"
                               value="{{ old('officer_code', $employee->officer_code) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">ເລກບັດປະຈຳຕົວ</label>
                        <input type="text" class="form-control" name="id_card_number"
                               value="{{ old('id_card_number', $employee->id_card_number) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ສະຖານະ <span class="text-danger">*</span></label>
                        <select class="form-select select2 @error('work_status_id') is-invalid @enderror" name="work_status_id">
                            <option value="">-- ເລືອກ --</option>
                            @foreach($workStatuses as $ws)
                                <option value="{{ $ws->id }}" {{ old('work_status_id', $employee->work_status_id) == $ws->id ? 'selected' : '' }}>
                                    {{ $ws->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('work_status_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ໝູ່ເລືອດ</label>
                        <select class="form-select" name="blood_group">
                            <option value="">--</option>
                            @foreach(['A','B','O','AB'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $employee->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ໝວດ II --}}
        <div class="card section-card">
            <div class="card-header d-flex align-items-center gap-2 fw-semibold">
                <div class="section-icon bg-success bg-opacity-10 text-success">II</div>
                ໝວດ II · ສະຖານທີ່ເກີດ
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ແຂວງ</label>
                        <select class="form-select select2 @error('birth_province_id') is-invalid @enderror"
                                name="birth_province_id" id="birthProvince">
                            <option value="">-- ເລືອກແຂວງ --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}" {{ old('birth_province_id', $employee->birth_province_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('birth_province_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ເມືອງ</label>
                        <select class="form-select select2 @error('birth_district_id') is-invalid @enderror"
                                name="birth_district_id" id="birthDistrict">
                            <option value="">-- ເລືອກເມືອງ --</option>
                        </select>
                        @error('birth_district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ບ້ານ</label>
                        <input type="text" class="form-control" name="birth_village"
                               value="{{ old('birth_village', $employee->birth_village) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ໝວດ III --}}
        <div class="card section-card">
            <div class="card-header d-flex align-items-center gap-2 fw-semibold">
                <div class="section-icon bg-info bg-opacity-10 text-info">III</div>
                ໝວດ III · ທີ່ຢູ່ປັດຈຸບັນ
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ແຂວງ</label>
                        <select class="form-select select2 @error('current_province_id') is-invalid @enderror"
                                name="current_province_id" id="currentProvince">
                            <option value="">-- ເລືອກແຂວງ --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}" {{ old('current_province_id', $employee->current_province_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('current_province_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ເມືອງ</label>
                        <select class="form-select select2 @error('current_district_id') is-invalid @enderror"
                                name="current_district_id" id="currentDistrict">
                            <option value="">-- ເລືອກເມືອງ --</option>
                        </select>
                        @error('current_district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ບ້ານ</label>
                        <input type="text" class="form-control" name="current_village"
                               value="{{ old('current_village', $employee->current_village) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ໝວດ IV --}}
        <div class="card section-card">
            <div class="card-header d-flex align-items-center gap-2 fw-semibold">
                <div class="section-icon bg-warning bg-opacity-10 text-warning">IV</div>
                ໝວດ IV · ການສຶກສາ
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ລະດັບວັດທະນະທຳ</label>
                        <input type="text" class="form-control" name="culture_level"
                               value="{{ old('culture_level', $employee->culture_level) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ລະດັບທິດສະດີ</label>
                        <input type="text" class="form-control" name="theory_level"
                               value="{{ old('theory_level', $employee->theory_level) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ຈາກສະຖາບັນ (ທິດສະດີ)</label>
                        <input type="text" class="form-control" name="theory_from"
                               value="{{ old('theory_from', $employee->theory_from) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ລະດັບວິຊາຊີບ</label>
                        <input type="text" class="form-control" name="profession_level"
                               value="{{ old('profession_level', $employee->profession_level) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ຈາກສະຖາບັນ (ວິຊາຊີບ)</label>
                        <input type="text" class="form-control" name="profession_from"
                               value="{{ old('profession_from', $employee->profession_from) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ໝວດ V --}}
        <div class="card section-card">
            <div class="card-header d-flex align-items-center gap-2 fw-semibold">
                <div class="section-icon bg-danger bg-opacity-10 text-danger">V</div>
                ໝວດ V · ຊົນເຜົ່າ ແລະ ສາສະໜາ
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">ສັນຊາດ</label>
                        <input type="text" class="form-control" name="nationality"
                               value="{{ old('nationality', $employee->nationality) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ກຸ່ມຊົນເຜົ່າ</label>
                        <input type="text" class="form-control" name="ethnicity_group"
                               value="{{ old('ethnicity_group', $employee->ethnicity_group) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ເຜົ່າ</label>
                        <input type="text" class="form-control" name="tribe"
                               value="{{ old('tribe', $employee->tribe) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ສາສະໜາ</label>
                        <input type="text" class="form-control" name="religion"
                               value="{{ old('religion', $employee->religion) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ຊັ້ນກ່ອນ 1975</label>
                        <input type="text" class="form-control" name="class_before_1975"
                               value="{{ old('class_before_1975', $employee->class_before_1975) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ຊັ້ນຫຼັງ 1975</label>
                        <input type="text" class="form-control" name="class_after_1975"
                               value="{{ old('class_after_1975', $employee->class_after_1975) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ໝວດ VI --}}
        <div class="card section-card">
            <div class="card-header d-flex align-items-center gap-2 fw-semibold">
                <div class="section-icon bg-secondary bg-opacity-10 text-secondary">VI</div>
                ໝວດ VI · ວັນທີສຳຄັນ
            </div>
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
                        <label class="form-label">{{ $label }}</label>
                        <input type="date" class="form-control @error($field) is-invalid @enderror"
                               name="{{ $field }}" value="{{ old($field, $employee->$field) }}">
                        @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ໝວດ VII --}}
        <div class="card section-card">
            <div class="card-header d-flex align-items-center gap-2 fw-semibold">
                <div class="section-icon bg-primary bg-opacity-10 text-primary">VII</div>
                ໝວດ VII · ຖອດຖື ແລະ ວິໄນ
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">ຊື່ພໍ່ແມ່</label>
                        <input type="text" class="form-control" name="parents_name"
                               value="{{ old('parents_name', $employee->parents_name) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ຊື່ຄູ່ສົມລົດ</label>
                        <input type="text" class="form-control" name="spouse_name"
                               value="{{ old('spouse_name', $employee->spouse_name) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ຈຳນວນລູກ</label>
                        <input type="number" class="form-control" name="child_count" id="childCount"
                               min="0" max="10" value="{{ old('child_count', $employee->child_count ?? 0) }}">
                    </div>
                </div>

                {{-- Children rows --}}
                <div id="childrenContainer"></div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">ໜ່ວຍງານທີ່ຜ່ານມາ</label>
                        <textarea class="form-control" name="previous_units" rows="3">{{ old('previous_units', $employee->previous_units) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ປະຫວັດວິໄນ</label>
                        <textarea class="form-control" name="discipline_record" rows="3">{{ old('discipline_record', $employee->discipline_record) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ໝວດ VIII --}}
        <div class="card section-card">
            <div class="card-header d-flex align-items-center gap-2 fw-semibold">
                <div class="section-icon bg-dark bg-opacity-10 text-dark">VIII</div>
                ໝວດ VIII · ຊີວະປະຫວັດ
            </div>
            <div class="card-body">
                <textarea class="form-control" name="biography" rows="5">{{ old('biography', $employee->biography) }}</textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mb-5">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save"></i> ບັນທຶກການແກ້ໄຂ
            </button>
            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-secondary">
                ຍົກເລີກ
            </a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('.select2').select2({ theme: 'bootstrap-5' });

    // ===== Province → District AJAX =====
    function loadDistricts(provinceId, targetSelector, selectedId = null) {
        const $select = $(targetSelector);
        $select.html('<option value="">ກຳລັງໂຫລດ...</option>').trigger('change');

        if (!provinceId) {
            $select.html('<option value="">-- ເລືອກເມືອງ --</option>').trigger('change');
            return;
        }

        $.get(`/provinces/${provinceId}/districts`)
            .done(data => {
                let html = '<option value="">-- ເລືອກເມືອງ --</option>';
                data.forEach(d => {
                    const sel = selectedId && d.id == selectedId ? 'selected' : '';
                    html += `<option value="${d.id}" ${sel}>${d.name}</option>`;
                });
                $select.html(html).trigger('change');
            })
            .fail(() => {
                $select.html('<option value="">ໂຫລດບໍ່ສຳເລັດ</option>').trigger('change');
            });
    }

    $('#birthProvince').on('change', function () {
        loadDistricts(this.value, '#birthDistrict');
    });

    $('#currentProvince').on('change', function () {
        loadDistricts(this.value, '#currentDistrict');
    });

    // Restore existing district values on page load
    @if($employee->birth_province_id)
        $('#birthProvince').val('{{ $employee->birth_province_id }}').trigger('change.select2');
        loadDistricts('{{ $employee->birth_province_id }}', '#birthDistrict', '{{ $employee->birth_district_id }}');
    @endif

    @if($employee->current_province_id)
        $('#currentProvince').val('{{ $employee->current_province_id }}').trigger('change.select2');
        loadDistricts('{{ $employee->current_province_id }}', '#currentDistrict', '{{ $employee->current_district_id }}');
    @endif

    // ===== Dynamic children rows =====
    const existingChildren = @json($employee->children ?? []);

    function renderChildren(count) {
        const container = document.getElementById('childrenContainer');
        if (count === 0) { container.innerHTML = ''; return; }
        let html = '';
        for (let i = 0; i < count; i++) {
            const c = existingChildren[i] || {};
            html += `
            <div class="card mb-2 p-3 bg-light">
                <div class="fw-semibold mb-2 small">ລູກຄົນທີ ${i + 1}</div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" name="children[${i}][first_name]"
                               placeholder="ຊື່" value="${c.first_name || ''}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" name="children[${i}][last_name]"
                               placeholder="ນາມສະກຸນ" value="${c.last_name || ''}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control form-control-sm" name="children[${i}][dob]"
                               value="${c.dob || ''}">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" name="children[${i}][gender]">
                            <option value="">ເພດ</option>
                            <option value="ຊາຍ" ${c.gender === 'ຊາຍ' ? 'selected' : ''}>ຊາຍ</option>
                            <option value="ຍິງ" ${c.gender === 'ຍິງ' ? 'selected' : ''}>ຍິງ</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" name="children[${i}][note]"
                               placeholder="ໝາຍເຫດ" value="${c.note || ''}">
                    </div>
                </div>
            </div>`;
        }
        container.innerHTML = html;
    }

    document.getElementById('childCount').addEventListener('input', function () {
        renderChildren(parseInt(this.value) || 0);
    });

    // Load existing children on page open
    renderChildren({{ $employee->child_count ?? 0 }});
});
</script>
@endpush
