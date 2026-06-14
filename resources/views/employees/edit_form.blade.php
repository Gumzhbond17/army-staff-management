@extends('layouts.mainLayout')

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

    {{--
    employees/edit.blade.php
    ════════════════════════════════════════════════════════════════════
    The form markup is 95% identical to create.blade.php.
    Only these things change:

      1. <form action> → route('employees.update', $employee)  +  @method('PUT')
      2. Every input value → old('field', $employee->field)
      3. Photo area shows existing photo if present
      4. @push('scripts') uses the edit_scripts.blade.php version

    Below is the complete list of every value= and selected= change,
    grouped by section, so you can apply them to your create.blade.php.
════════════════════════════════════════════════════════════════════ --}}

{{-- ===== FORM TAG ===== --}}
<form action="{{ route('employees.update', $employee) }}"
      method="POST"
      enctype="multipart/form-data"
      id="employeeForm"
      novalidate>
    @csrf
    @method('PUT')

    {{-- ════════════════════════════════════════════
         SECTION I · ຂໍ້ມູນທົ່ວໄປ
    ════════════════════════════════════════════ --}}

    {{-- Photo — show existing photo when present --}}
    {{-- @php $existingPhoto = $employee->photo ? Storage::url($employee->photo) : null; @endphp --}}
    @php $existingPhoto = $employee->photo ? asset('storage/' . $employee->photo) : null; @endphp
    <div class="photo-upload-area w-100">
        <input type="file" id="photoInput" name="photo" accept="image/*" onchange="previewPhoto(this)">
        <img id="photoPreview"
             class="photo-preview"
             src="{{ $existingPhoto ?? '#' }}"
             alt="preview"
             style="{{ $existingPhoto ? '' : 'display:none' }}">
        <div id="uploadPlaceholder" style="{{ $existingPhoto ? 'display:none' : '' }}">
            <div class="upload-icon"><i class="bi bi-camera"></i></div>
            <p class="mb-0 text-muted" style="font-size:0.75rem;">ກົດເພື່ອອັບໂຫຼດ<br><span class="text-primary">JPG, PNG</span></p>
        </div>
    </div>

    {{-- Full Name --}}
    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
           name="full_name"
           value="{{ old('full_name', $employee->full_name) }}"
           placeholder="ຊື່ ແລະ ນາມສະກຸນ" required>

    {{-- Gender --}}
    <input type="radio" name="gender" id="genderMale" value="male"
           {{ old('gender', $employee->gender) == 'male' ? 'checked' : '' }}>
    <input type="radio" name="gender" id="genderFemale" value="female"
           {{ old('gender', $employee->gender) == 'female' ? 'checked' : '' }}>

    {{-- DOB --}}
    <input type="date" class="form-control @error('dob') is-invalid @enderror"
           name="dob"
           value="{{ old('dob', $employee->dob?->format('Y-m-d')) }}">

    {{-- Officer Code --}}
    <input type="text" class="form-control @error('officer_code') is-invalid @enderror"
           name="officer_code"
           value="{{ old('officer_code', $employee->officer_code) }}"
           maxlength="12">

    {{-- ID Card --}}
    <input type="text" class="form-control @error('id_card_number') is-invalid @enderror"
           name="id_card_number"
           value="{{ old('id_card_number', $employee->id_card_number) }}"
           maxlength="25">

    {{-- Unit --}}
    <select class="form-select select2 @error('unit_id') is-invalid @enderror"
            name="unit_id" id="unitSelect" required>
        <option value="">-- ເລືອກກອງ --</option>
        @foreach($units as $unit)
            <option value="{{ $unit->id }}"
                {{ old('unit_id', $employee->unit_id) == $unit->id ? 'selected' : '' }}>
                {{ $unit->name }}
            </option>
        @endforeach
    </select>

    {{-- Work Status --}}
    <select class="form-select select2 @error('work_status_id') is-invalid @enderror"
            name="work_status_id" id="workStatusSelect" required>
        <option value="">-- ເລືອກສະຖານະ --</option>
        @foreach($workStatuses as $status)
            <option value="{{ $status->id }}"
                {{ old('work_status_id', $employee->work_status_id) == $status->id ? 'selected' : '' }}>
                {{ $status->name }}
            </option>
        @endforeach
    </select>

    {{-- Party Duty --}}
    <input type="text" class="form-control @error('party_duty') is-invalid @enderror"
           name="party_duty"
           value="{{ old('party_duty', $employee->party_duty) }}">

    {{-- Command Duty --}}
    <input type="text" class="form-control @error('command_duty') is-invalid @enderror"
           name="command_duty"
           value="{{ old('command_duty', $employee->command_duty) }}">

    {{-- Blood Group --}}
    @foreach(['A','B','O','AB'] as $bg)
        <input type="radio" name="blood_group" id="blood_{{ $bg }}" value="{{ $bg }}"
               {{ old('blood_group', $employee->blood_group) == $bg ? 'checked' : '' }}>
    @endforeach

    {{-- ════════════════════════════════════════════
         SECTION II · ສະຖານທີ່ເກີດ
         Province is pre-selected via $bProvince in JS (edit_scripts.blade.php).
         District is loaded by loadDistricts() on page load.
    ════════════════════════════════════════════ --}}

    {{-- Birth Province — value pre-set via JS, but keep selected in loop as fallback --}}
    <select class="form-select select2 @error('birth_province_id') is-invalid @enderror"
            name="birth_province_id" id="birthProvince">
        <option value="">-- ເລືອກແຂວງ --</option>
        @foreach($provinces as $p)
            <option value="{{ $p->id }}"
                {{ old('birth_province_id', $employee->birth_province_id) == $p->id ? 'selected' : '' }}>
                {{ $p->name }}
            </option>
        @endforeach
    </select>

    {{-- Birth District — populated via AJAX in JS --}}
    <select class="form-select select2 @error('birth_district_id') is-invalid @enderror"
            name="birth_district_id" id="birthDistrict">
        <option value="">-- ເລືອກເມືອງ --</option>
        {{-- Options injected by loadDistricts() in JS --}}
    </select>

    {{-- Birth Village --}}
    <input type="text" class="form-control @error('birth_village') is-invalid @enderror"
           name="birth_village"
           value="{{ old('birth_village', $employee->birth_village) }}">

    {{-- ════════════════════════════════════════════
         SECTION III · ທີ່ຢູ່ປັດຈຸບັນ
    ════════════════════════════════════════════ --}}

    <select class="form-select select2 @error('current_province_id') is-invalid @enderror"
            name="current_province_id" id="currentProvince">
        <option value="">-- ເລືອກແຂວງ --</option>
        @foreach($provinces as $p)
            <option value="{{ $p->id }}"
                {{ old('current_province_id', $employee->current_province_id) == $p->id ? 'selected' : '' }}>
                {{ $p->name }}
            </option>
        @endforeach
    </select>

    <select class="form-select select2 @error('current_district_id') is-invalid @enderror"
            name="current_district_id" id="currentDistrict">
        <option value="">-- ເລືອກເມືອງ --</option>
    </select>

    <input type="text" class="form-control @error('current_village') is-invalid @enderror"
           name="current_village" id="currentVillage"
           value="{{ old('current_village', $employee->current_village) }}">

    {{-- ════════════════════════════════════════════
         SECTION IV · ການສຶກສາ
    ════════════════════════════════════════════ --}}

    <input type="text" class="form-control" name="culture_level"
           value="{{ old('culture_level', $employee->culture_level) }}">
    <input type="text" class="form-control" name="theory_level"
           value="{{ old('theory_level', $employee->theory_level) }}">
    <input type="text" class="form-control" name="theory_from"
           value="{{ old('theory_from', $employee->theory_from) }}">
    <input type="text" class="form-control" name="profession_level"
           value="{{ old('profession_level', $employee->profession_level) }}">
    <input type="text" class="form-control" name="profession_from"
           value="{{ old('profession_from', $employee->profession_from) }}">

    {{-- ════════════════════════════════════════════
         SECTION V · ສັນຊາດ / ຊົນເຜົ່າ
    ════════════════════════════════════════════ --}}

    <input type="text" class="form-control" name="nationality"
           value="{{ old('nationality', $employee->nationality) }}">
    <input type="text" class="form-control" name="ethnicity_group"
           value="{{ old('ethnicity_group', $employee->ethnicity_group) }}">
    <input type="text" class="form-control" name="tribe"
           value="{{ old('tribe', $employee->tribe) }}">
    <input type="text" class="form-control" name="religion"
           value="{{ old('religion', $employee->religion) }}">
    <input type="text" class="form-control" name="class_before_1975"
           value="{{ old('class_before_1975', $employee->class_before_1975) }}">
    <input type="text" class="form-control" name="class_after_1975"
           value="{{ old('class_after_1975', $employee->class_after_1975) }}">

    {{-- ════════════════════════════════════════════
         SECTION VI · ວັນທີສຳຄັນ
    ════════════════════════════════════════════ --}}

    @php
        $dateFields = [
            'join_revolution_date' => 'ວັນເຂົ້າປະຕິວັດ',
            'join_army_date'       => 'ວັນເຂົ້າທະຫານ',
            'candidate_party_date' => 'ວັນເຂົ້າພັກສຳຮອງ',
            'full_party_date'      => 'ວັນເຂົ້າພັກສົມບູນ',
            'current_rank_date'    => 'ວັນໄດ້ຊັ້ນປັດຈຸບັນ',
        ];
    @endphp

    @foreach($dateFields as $field => $label)
        <input type="date" class="form-control @error($field) is-invalid @enderror"
               name="{{ $field }}"
               value="{{ old($field, $employee->$field?->format('Y-m-d')) }}">
    @endforeach

    {{-- ════════════════════════════════════════════
         SECTION VII · ຄອບຄົວ ແລະ ວິໄນ
    ════════════════════════════════════════════ --}}

    <input type="text" class="form-control" name="parents_name"
           value="{{ old('parents_name', $employee->parents_name) }}">
    <input type="text" class="form-control" name="spouse_name"
           value="{{ old('spouse_name', $employee->spouse_name) }}">

    {{-- child_count — seeded from DB on first load --}}
    <input type="number" class="form-control" name="child_count" id="childCount"
           min="0" max="10"
           value="{{ old('child_count', $employee->children->count()) }}">

    <div id="childrenContainer" class="mt-3"></div>
    {{-- Children rows are rendered by renderChildren(oldChildCount) in JS --}}

    <textarea class="form-control @error('previous_units') is-invalid @enderror"
              name="previous_units" rows="3">{{ old('previous_units', $employee->previous_units) }}</textarea>

    <textarea class="form-control @error('discipline_record') is-invalid @enderror"
              name="discipline_record" rows="3">{{ old('discipline_record', $employee->discipline_record) }}</textarea>

    {{-- ════════════════════════════════════════════
         SECTION VIII · ປະຫວັດ
    ════════════════════════════════════════════ --}}

    <textarea class="form-control @error('biography') is-invalid @enderror"
              name="biography" rows="6">{{ old('biography', $employee->biography) }}</textarea>

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
    /* @if($employee->birth_province_id)
        $('#birthProvince').val('{{ $employee->birth_province_id }}').trigger('change.select2');
        loadDistricts('{{ $employee->birth_province_id }}', '#birthDistrict', '{{ $employee->birth_district_id }}');
    @endif

    @if($employee->current_province_id)
        $('#currentProvince').val('{{ $employee->current_province_id }}').trigger('change.select2');
        loadDistricts('{{ $employee->current_province_id }}', '#currentDistrict', '{{ $employee->current_district_id }}');
    @endif */

    @php
        $bProvince = old('birth_province_id', $employee->birth_province_id ?? null);
        $bDistrict = old('birth_district_id',  $employee->birth_district_id ?? null);
    @endphp
    @if($bProvince)
        loadDistricts('{{ $bProvince }}', '#birthDistrict', '{{ $bDistrict }}');
    @endif



    // ===== Dynamic children rows =====
    const existingChildren = @json($employeeChildren);

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
                            <option value="male" ${c.gender === 'male' ? 'selected' : ''}>ຊາຍ</option>
                            <option value="female" ${c.gender === 'female' ? 'selected' : ''}>ຍິງ</option>
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
    // renderChildren({{ $employee->child_count ?? 0 }});
    const oldChildCount = parseInt("{{ old('child_count', $employee->children->count()) }}") || 0;
        renderChildren(oldChildCount);
    });
</script>
@endpush
