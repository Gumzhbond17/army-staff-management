@extends('layouts.mainLayout')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    body {
        background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 30%, #e0e7ff 60%, #f5f3ff 100%) !important;
        background-attachment: fixed !important;
    }
    body::before {
        content: '';
        position: fixed;
        top: -150px; left: -150px;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    body::after {
        content: '';
        position: fixed;
        bottom: -150px; right: -100px;
        width: 450px; height: 450px;
        background: radial-gradient(circle, rgba(14,165,233,0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .page-wrapper { position: relative; z-index: 1; padding: 1.5rem 0 4rem; }
    .page-header {
        background: rgba(255,255,255,0.55);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.75);
        border-radius: 20px;
        padding: 1.25rem 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 24px rgba(99,102,241,0.08);
    }
    .page-header h2 { font-size: 1.4rem; font-weight: 700; color: #3730a3; margin: 0; }
    .page-header p  { color: #64748b; margin: 0.2rem 0 0; font-size: 0.85rem; }
    .glass-card {
        background: rgba(255,255,255,0.60);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,0.80);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.10), 0 1px 0 rgba(255,255,255,0.9) inset;
        overflow: hidden;
    }
    .section-header {
        background: linear-gradient(90deg, rgba(99,102,241,0.10) 0%, rgba(14,165,233,0.06) 100%);
        border-bottom: 1px solid rgba(99,102,241,0.12);
        padding: 0.85rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .section-header .section-icon {
        width: 30px; height: 30px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .section-header h6 { font-weight: 600; color: #3730a3; margin: 0; font-size: 0.875rem; letter-spacing: 0.3px; }
    .card-body-inner { padding: 1.25rem 1.5rem; }
    .form-label { font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.3rem; }
    .required-star { color: #ef4444; margin-left: 2px; }
    .form-control, .form-select {
        background: rgba(255,255,255,0.70) !important;
        border: 1px solid rgba(148,163,184,0.45) !important;
        border-radius: 10px !important;
        padding: 0.5rem 0.8rem;
        font-size: 0.875rem;
        color: #1e293b;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
    }
    .form-control:focus, .form-select:focus {
        background: rgba(255,255,255,0.90) !important;
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15) !important;
        outline: none;
    }
    .form-control::placeholder { color: #94a3b8; }
    textarea.form-control { resize: vertical; min-height: 88px; }
    .select2-container--bootstrap-5 .select2-selection {
        background: rgba(255,255,255,0.70) !important;
        border: 1px solid rgba(148,163,184,0.45) !important;
        border-radius: 10px !important;
        font-size: 0.875rem !important;
        min-height: 38px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15) !important;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        border-color: rgba(99,102,241,0.3) !important;
        border-radius: 10px !important;
        backdrop-filter: blur(16px);
        background: rgba(255,255,255,0.95) !important;
    }
    .input-group .input-group-text {
        background: rgba(99,102,241,0.08);
        border: 1px solid rgba(148,163,184,0.45);
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #6366f1;
        font-size: 0.875rem;
    }
    .input-group .form-control, .input-group .form-select { border-radius: 0 10px 10px 0 !important; border-left: none !important; }
    .input-group .form-control:focus, .input-group .form-select:focus { border-left: none !important; }
    .photo-upload-area {
        border: 2px dashed rgba(99,102,241,0.35);
        border-radius: 14px;
        background: rgba(99,102,241,0.03);
        padding: 1.5rem 0.75rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
    }
    .photo-upload-area:hover { border-color: #6366f1; background: rgba(99,102,241,0.07); }
    .photo-upload-area input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; z-index: 10;
    }
    .photo-preview {
        width: 90px; height: 110px;
        border-radius: 10px;
        object-fit: cover;
        border: 3px solid rgba(99,102,241,0.25);
        margin-bottom: 0.5rem;
        display: none;
    }
    .upload-icon {
        width: 48px; height: 48px;
        background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(139,92,246,0.12));
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 0.5rem;
        font-size: 1.25rem;
        color: #6366f1;
    }
    .gender-group { display: flex; gap: 0.6rem; flex-wrap: wrap; }
    .gender-btn input { display: none; }
    .gender-btn label {
        display: flex; align-items: center; gap: 0.4rem;
        padding: 0.45rem 1.1rem;
        border: 1.5px solid rgba(148,163,184,0.4);
        border-radius: 50px;
        background: rgba(255,255,255,0.6);
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        color: #64748b;
        transition: all 0.2s;
    }
    .gender-btn input:checked + label {
        border-color: #6366f1;
        background: rgba(99,102,241,0.10);
        color: #4338ca;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
    }
    .blood-group { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .blood-btn input { display: none; }
    .blood-btn label {
        width: 48px; height: 48px;
        display: flex; align-items: center; justify-content: center;
        border: 1.5px solid rgba(148,163,184,0.4);
        border-radius: 10px;
        background: rgba(255,255,255,0.6);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s;
    }
    .blood-btn input:checked + label {
        border-color: #ef4444;
        background: rgba(239,68,68,0.10);
        color: #dc2626;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
    }
    .section-divider { height: 1px; background: linear-gradient(90deg, transparent, rgba(99,102,241,0.15), transparent); margin: 0.25rem 0; }
    .btn-glass-primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border: none; border-radius: 10px; color: #fff;
        font-weight: 600; font-size: 0.875rem; padding: 0.6rem 1.75rem;
        box-shadow: 0 4px 15px rgba(99,102,241,0.35);
        transition: all 0.25s ease;
    }
    .btn-glass-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); color: #fff; }
    .btn-glass-secondary {
        background: rgba(255,255,255,0.7);
        border: 1.5px solid rgba(148,163,184,0.4);
        border-radius: 10px; color: #64748b;
        font-weight: 500; font-size: 0.875rem; padding: 0.6rem 1.25rem;
        transition: all 0.25s ease;
    }
    .btn-glass-secondary:hover { background: rgba(255,255,255,0.9); border-color: #6366f1; color: #6366f1; }
    .form-footer {
        background: rgba(255,255,255,0.55);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.75);
        border-radius: 20px;
        padding: 1.1rem 1.5rem;
        margin-top: 1.25rem;
        box-shadow: 0 4px 24px rgba(99,102,241,0.08);
    }
    .step-nav { display: flex; gap: 0.3rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
    .step-pill {
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 600;
        background: rgba(255,255,255,0.6);
        border: 1.5px solid rgba(148,163,184,0.3);
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .step-pill.active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 2px 8px rgba(99,102,241,0.3);
    }
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.12) !important;
    }
    .invalid-feedback { font-size: 0.76rem; color: #ef4444; }
</style>
@endpush

@section('content')

@php $existingPhoto = $employee->photoUrl; @endphp

<div class="page-wrapper">

    @if(session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success', title: 'ສຳເລັດ',
            text: '{{ session('success') }}',
            confirmButtonColor: '#6366f1', timer: 2500, showConfirmButton: false,
        });
    });
    </script>
    @endif

    @if($errors->any())
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error', title: 'ມີຂໍ້ຜິດພາດ',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#6366f1',
        });
    });
    </script>
    @endif

    {{-- Page Header --}}
    <div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h2><i class="bi bi-pencil-square me-2"></i>ແກ້ໄຂຂໍ້ມູນພະນັກງານ</h2>
            <p>ກະລຸນາກວດສອບ ແລະ ແກ້ໄຂຂໍ້ມູນໃຫ້ຖືກຕ້ອງ</p>
        </div>
        <a href="{{ route('employees.show', $employee->id) }}" class="btn-glass-secondary btn">
            <i class="bi bi-arrow-left me-1"></i> ກັບຄືນ
        </a>
    </div>

    {{-- Section Jump Nav --}}
    <div class="step-nav">
        <a href="#sec1" class="step-pill active">I · ຂໍ້ມູນທົ່ວໄປ</a>
        <a href="#sec2" class="step-pill">II · ສະຖານທີ່ເກີດ</a>
        <a href="#sec3" class="step-pill">III · ທີ່ຢູ່ປັດຈຸບັນ</a>
        <a href="#sec4" class="step-pill">IV · ການສຶກສາ</a>
        <a href="#sec5" class="step-pill">V · ສັນຊາດ / ຊົນເຜົ່າ</a>
        <a href="#sec6" class="step-pill">VI · ວັນທີສຳຄັນ</a>
        <a href="#sec7" class="step-pill">VII · ຄອບຄົວ</a>
        <a href="#sec8" class="step-pill">VIII · ປະຫວັດ</a>
    </div>

    <form action="{{ route('employees.update', $employee) }}"
          method="POST"
          enctype="multipart/form-data"
          id="employeeForm"
          novalidate>
        @csrf
        @method('PUT')

        {{-- ================================================================
             ໝວດ I: ຂໍ້ມູນທົ່ວໄປ
        ================================================================ --}}
        <div class="glass-card mb-4" id="sec1">
            <div class="section-header">
                <div class="section-icon"><i class="bi bi-person-badge"></i></div>
                <h6>ໝວດ I · ຂໍ້ມູນທົ່ວໄປ</h6>
            </div>
            <div class="card-body-inner">
                <div class="row g-3">

                    {{-- Photo --}}
                    <div class="col-lg-2 col-md-3 d-flex flex-column align-items-center">
                        <label class="form-label text-center w-100">ຮູບຖ່າຍ</label>
                        <div class="photo-upload-area w-100">
                            <input type="file" id="photoInput" name="photo" accept="image/*" onchange="previewPhoto(this)">
                            <img id="photoPreview"
                                 class="photo-preview"
                                 src="{{ $existingPhoto ?? '#' }}"
                                 alt="preview"
                                 style="{{ $existingPhoto ? 'display:block' : '' }}">
                            <div id="uploadPlaceholder" style="{{ $existingPhoto ? 'display:none' : '' }}">
                                <div class="upload-icon"><i class="bi bi-camera"></i></div>
                                <p class="mb-0 text-muted" style="font-size:0.75rem;">ກົດເພື່ອອັບໂຫຼດ<br><span class="text-primary">JPG, PNG</span></p>
                            </div>
                        </div>
                        <p id="photoFileName" class="text-muted mt-1 mb-0" style="font-size:0.7rem; text-align:center; display:none;"></p>
                    </div>

                    <div class="col-lg-10 col-md-9">
                        <div class="row g-3">

                            {{-- Full Name --}}
                            <div class="col-md-6">
                                <label class="form-label">ຊື່ ແລະ ນາມສະກຸນ <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                           name="full_name"
                                           value="{{ old('full_name', $employee->full_name) }}"
                                           placeholder="ຊື່ ແລະ ນາມສະກຸນ" required>
                                    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Gender --}}
                            <div class="col-md-6">
                                <label class="form-label">ເພດ</label>
                                <div class="gender-group mt-1">
                                    <div class="gender-btn">
                                        <input type="radio" name="gender" id="genderMale" value="male"
                                               {{ old('gender', $employee->gender) == 'male' ? 'checked' : '' }}>
                                        <label for="genderMale"><i class="bi bi-gender-male text-primary"></i> ຊາຍ</label>
                                    </div>
                                    <div class="gender-btn">
                                        <input type="radio" name="gender" id="genderFemale" value="female"
                                               {{ old('gender', $employee->gender) == 'female' ? 'checked' : '' }}>
                                        <label for="genderFemale"><i class="bi bi-gender-female text-danger"></i> ຍິງ</label>
                                    </div>
                                </div>
                            </div>

                            {{-- DOB --}}
                            <div class="col-md-4">
                                <label class="form-label">ວັນເດືອນປີເກີດ</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                    <input type="date" class="form-control @error('dob') is-invalid @enderror"
                                           name="dob"
                                           value="{{ old('dob', $employee->dob?->format('Y-m-d')) }}">
                                    @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Officer Code --}}
                            <div class="col-md-4">
                                <label class="form-label">ລະຫັດນາຍທະຫານ</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-star"></i></span>
                                    <input type="text" class="form-control @error('officer_code') is-invalid @enderror"
                                           name="officer_code"
                                           value="{{ old('officer_code', $employee->officer_code) }}"
                                           placeholder="ສູງສຸດ 12 ຕົວ" maxlength="12">
                                    @error('officer_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- ID Card --}}
                            <div class="col-md-4">
                                <label class="form-label">ລະຫັດບັດປະຈຳຕົວ</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-credit-card-2-front"></i></span>
                                    <input type="text" class="form-control @error('id_card_number') is-invalid @enderror"
                                           name="id_card_number"
                                           value="{{ old('id_card_number', $employee->id_card_number) }}"
                                           placeholder="ສູງສຸດ 25 ຕົວ" maxlength="25">
                                    @error('id_card_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Unit --}}
                            <div class="col-md-6">
                                <label class="form-label">ກອງປະຈຳ <span class="required-star">*</span></label>
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
                                @error('unit_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Work Status --}}
                            <div class="col-md-6">
                                <label class="form-label">ສະຖານະການເຮັດວຽກ <span class="required-star">*</span></label>
                                <select class="form-select select2 @error('work_status_id') is-invalid @enderror"
                                        name="work_status_id" id="workStatusSelect" required>
                                    <option value="">-- ເລືອກສະຖານະ --</option>
                                    @foreach($workingStatuses as $status)
                                        <option value="{{ $status->id }}"
                                            {{ old('work_status_id', $employee->work_status_id) == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('work_status_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Party Duty --}}
                            <div class="col-md-6">
                                <label class="form-label">ໜ້າທີ່ຮັບຜິດຊອບພັກ</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-flag"></i></span>
                                    <input type="text" class="form-control @error('party_duty') is-invalid @enderror"
                                           name="party_duty"
                                           value="{{ old('party_duty', $employee->party_duty) }}"
                                           placeholder="ໜ້າທີ່ພັກ">
                                    @error('party_duty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Command Duty --}}
                            <div class="col-md-6">
                                <label class="form-label">ໜ້າທີ່ຮັບຜິດຊອບບັນຊາ</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    <input type="text" class="form-control @error('command_duty') is-invalid @enderror"
                                           name="command_duty"
                                           value="{{ old('command_duty', $employee->command_duty) }}"
                                           placeholder="ໜ້າທີ່ບັນຊາ">
                                    @error('command_duty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Blood Group --}}
                            <div class="col-12">
                                <label class="form-label">ໝູ່ເລືອດ</label>
                                <div class="blood-group">
                                    @foreach(['A','B','O','AB'] as $bg)
                                    <div class="blood-btn">
                                        <input type="radio" name="blood_group" id="blood_{{ $bg }}" value="{{ $bg }}"
                                               {{ old('blood_group', $employee->blood_group) == $bg ? 'checked' : '' }}>
                                        <label for="blood_{{ $bg }}">{{ $bg }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================
             ໝວດ II: ສະຖານທີ່ເກີດ
        ================================================================ --}}
        <div class="glass-card mb-4" id="sec2">
            <div class="section-header">
                <div class="section-icon" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8)"><i class="bi bi-geo-alt"></i></div>
                <h6>ໝວດ II · ສະຖານທີ່ເກີດ</h6>
            </div>
            <div class="card-body-inner">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ແຂວງ</label>
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
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                            <input type="text" class="form-control @error('birth_village') is-invalid @enderror"
                                   name="birth_village"
                                   value="{{ old('birth_village', $employee->birth_village) }}"
                                   placeholder="ຊື່ບ້ານ">
                            @error('birth_village')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================
             ໝວດ III: ທີ່ຢູ່ປັດຈຸບັນ
        ================================================================ --}}
        <div class="glass-card mb-4" id="sec3">
            <div class="section-header">
                <div class="section-icon" style="background:linear-gradient(135deg,#10b981,#34d399)"><i class="bi bi-house-check"></i></div>
                <h6>ໝວດ III · ທີ່ຢູ່ປັດຈຸບັນ</h6>
            </div>
            <div class="card-body-inner">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ແຂວງ</label>
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
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                            <input type="text" class="form-control @error('current_village') is-invalid @enderror"
                                   name="current_village" id="currentVillage"
                                   value="{{ old('current_village', $employee->current_village) }}"
                                   placeholder="ຊື່ບ້ານ">
                            @error('current_village')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================
             ໝວດ IV: ການສຶກສາ
        ================================================================ --}}
        <div class="glass-card mb-4" id="sec4">
            <div class="section-header">
                <div class="section-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)"><i class="bi bi-mortarboard"></i></div>
                <h6>ໝວດ IV · ການສຶກສາ</h6>
            </div>
            <div class="card-body-inner">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ລະດັບວັດທະນະທຳ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-book"></i></span>
                            <input type="text" class="form-control" name="culture_level"
                                   value="{{ old('culture_level', $employee->culture_level) }}" placeholder="ລະດັບວັດທະນະທຳ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ລະດັບທິດສະດີ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                            <input type="text" class="form-control" name="theory_level"
                                   value="{{ old('theory_level', $employee->theory_level) }}" placeholder="ລະດັບທິດສະດີ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ຮຽນທິດສະດີຈາກ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-building-check"></i></span>
                            <input type="text" class="form-control" name="theory_from"
                                   value="{{ old('theory_from', $employee->theory_from) }}" placeholder="ສະຖາບັນ / ໂຮງຮຽນ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ລະດັບວິຊາສະເພາະ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-award"></i></span>
                            <input type="text" class="form-control" name="profession_level"
                                   value="{{ old('profession_level', $employee->profession_level) }}" placeholder="ລະດັບວິຊາສະເພາະ">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">ຮຽນວິຊາສະເພາະຈາກ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-building-check"></i></span>
                            <input type="text" class="form-control" name="profession_from"
                                   value="{{ old('profession_from', $employee->profession_from) }}" placeholder="ສະຖາບັນ / ມະຫາວິທະຍາໄລ">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================
             ໝວດ V: ສັນຊາດ / ຊົນເຜົ່າ / ຊົນຊັ້ນ
        ================================================================ --}}
        <div class="glass-card mb-4" id="sec5">
            <div class="section-header">
                <div class="section-icon" style="background:linear-gradient(135deg,#ec4899,#f472b6)"><i class="bi bi-globe-asia-australia"></i></div>
                <h6>ໝວດ V · ສັນຊາດ / ຊົນເຜົ່າ / ຊົນຊັ້ນ</h6>
            </div>
            <div class="card-body-inner">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ສັນຊາດ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-flag"></i></span>
                            <input type="text" class="form-control" name="nationality"
                                   value="{{ old('nationality', $employee->nationality) }}" placeholder="ສັນຊາດ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ເຊື້ອຊາດ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                            <input type="text" class="form-control" name="ethnicity_group"
                                   value="{{ old('ethnicity_group', $employee->ethnicity_group) }}" placeholder="ເຊື້ອຊາດ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ຊົນເຜົ່າ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-lines-fill"></i></span>
                            <input type="text" class="form-control" name="tribe"
                                   value="{{ old('tribe', $employee->tribe) }}" placeholder="ຊົນເຜົ່າ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ສາດສະໜາ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-yin-yang"></i></span>
                            <input type="text" class="form-control" name="religion"
                                   value="{{ old('religion', $employee->religion) }}" placeholder="ສາດສະໜາ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ຊົນຊັ້ນກ່ອນປີ 1975</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock-history"></i></span>
                            <input type="text" class="form-control" name="class_before_1975"
                                   value="{{ old('class_before_1975', $employee->class_before_1975) }}" placeholder="ຊົນຊັ້ນ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ຊົນຊັ້ນຫຼັງປີ 1975</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock-fill"></i></span>
                            <input type="text" class="form-control" name="class_after_1975"
                                   value="{{ old('class_after_1975', $employee->class_after_1975) }}" placeholder="ຊົນຊັ້ນ">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================
             ໝວດ VI: ວັນທີສຳຄັນ
        ================================================================ --}}
        <div class="glass-card mb-4" id="sec6">
            <div class="section-header">
                <div class="section-icon" style="background:linear-gradient(135deg,#f97316,#fb923c)"><i class="bi bi-calendar-event"></i></div>
                <h6>ໝວດ VI · ວັນທີສຳຄັນ</h6>
            </div>
            <div class="card-body-inner">
                <div class="row g-3">
                    @php
                        $dateFields = [
                            'join_revolution_date' => ['label' => 'ວັນເຂົ້າປະຕິວັດ',   'icon' => 'bi-flag-fill'],
                            'join_army_date'       => ['label' => 'ວັນເຂົ້າທະຫານ',      'icon' => 'bi-shield-fill'],
                            'candidate_party_date' => ['label' => 'ວັນເຂົ້າພັກສຳຮອງ',   'icon' => 'bi-calendar-plus'],
                            'full_party_date'      => ['label' => 'ວັນເຂົ້າພັກສົມບູນ',  'icon' => 'bi-calendar-check'],
                            'current_rank_date'    => ['label' => 'ວັນໄດ້ຊັ້ນປັດຈຸບັນ', 'icon' => 'bi-award-fill'],
                        ];
                    @endphp
                    @foreach($dateFields as $field => $meta)
                    <div class="col-md-4">
                        <label class="form-label">{{ $meta['label'] }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi {{ $meta['icon'] }}"></i></span>
                            <input type="date" class="form-control @error($field) is-invalid @enderror"
                                   name="{{ $field }}"
                                   value="{{ old($field, $employee->$field?->format('Y-m-d')) }}">
                            @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ================================================================
             ໝວດ VII: ຄອບຄົວ + ວິໄນ
        ================================================================ --}}
        <div class="glass-card mb-4" id="sec7">
            <div class="section-header">
                <div class="section-icon" style="background:linear-gradient(135deg,#14b8a6,#2dd4bf)"><i class="bi bi-people-fill"></i></div>
                <h6>ໝວດ VII · ຄອບຄົວ ແລະ ວິໄນ</h6>
            </div>
            <div class="card-body-inner">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">ຊື່ພໍ່ແມ່</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-heart"></i></span>
                            <input type="text" class="form-control" name="parents_name"
                                   value="{{ old('parents_name', $employee->parents_name) }}" placeholder="ຊື່ພໍ່ ແລະ ແມ່">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">ຊື່ຄູ່ຊີວິດ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                            <input type="text" class="form-control" name="spouse_name"
                                   value="{{ old('spouse_name', $employee->spouse_name) }}" placeholder="ຊື່ຄູ່ຊີວິດ">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ຈຳນວນລູກ</label>
                        <input type="number" class="form-control" name="child_count" id="childCount"
                               min="0" max="10"
                               value="{{ old('child_count', $employee->children->count()) }}">
                    </div>

                    <div id="childrenContainer" class="mt-3 col-12"></div>

                    <div class="col-12"><div class="section-divider"></div></div>

                    <div class="col-md-6">
                        <label class="form-label">ກອງເກົ່າທີ່ເຄີຍສັງກັດ</label>
                        <textarea class="form-control @error('previous_units') is-invalid @enderror"
                                  name="previous_units" rows="3"
                                  placeholder="ລະບຸກອງເກົ່າ ໂດຍລຽງຕາມລຳດັບ...">{{ old('previous_units', $employee->previous_units) }}</textarea>
                        @error('previous_units')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ປະຫວັດການວິໄນ</label>
                        <textarea class="form-control @error('discipline_record') is-invalid @enderror"
                                  name="discipline_record" rows="3"
                                  placeholder="ປະຫວັດການຖືກວິໄນ (ຖ້າມີ)...">{{ old('discipline_record', $employee->discipline_record) }}</textarea>
                        @error('discipline_record')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================
             ໝວດ VIII: ປະຫວັດການເຄື່ອນໄຫວ
        ================================================================ --}}
        <div class="glass-card mb-4" id="sec8">
            <div class="section-header">
                <div class="section-icon" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa)"><i class="bi bi-journal-text"></i></div>
                <h6>ໝວດ VIII · ປະຫວັດການເຄື່ອນໄຫວ</h6>
            </div>
            <div class="card-body-inner">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">
                            ປະຫວັດການເຄື່ອນໄຫວ
                            <span class="text-muted ms-1" style="font-size:0.73rem;">(ແຕ່ອາຍຸ 8 ປີ)</span>
                        </label>
                        <textarea class="form-control @error('biography') is-invalid @enderror"
                                  name="biography" rows="6"
                                  placeholder="ລະບຸປະຫວັດການເຄື່ອນໄຫວ ແຕ່ອາຍຸ 8 ປີ ຂຶ້ນໄປ...">{{ old('biography', $employee->biography) }}</textarea>
                        @error('biography')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Footer --}}
        <div class="form-footer d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2 text-muted" style="font-size:0.78rem;">
                <i class="bi bi-info-circle text-primary"></i>
                ຊ່ອງທີ່ມີ <span class="required-star">*</span> ຕ້ອງການຂໍ້ມູນ
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('employees.show', $employee->id) }}" class="btn-glass-secondary btn">
                    <i class="bi bi-x-circle me-1"></i> ຍົກເລີກ
                </a>
                <button type="submit" class="btn-glass-primary btn">
                    <i class="bi bi-check-circle me-2"></i> ບັນທຶກການແກ້ໄຂ
                </button>
            </div>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
    const existingChildren = @json($employeeChildren);
    const oldChildren      = @json(old('children', []));
    const childData        = oldChildren.length > 0 ? oldChildren : existingChildren;

    function loadDistricts(provinceId, targetSelector, selectedId = null) {
        const $select = $(targetSelector);
        $select.html('<option value="">ກຳລັງໂຫຼດ...</option>').trigger('change');
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
            .fail(() => $select.html('<option value="">ໂຫຼດບໍ່ສຳເລັດ</option>').trigger('change'));
    }

    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = e => {
                $('#photoPreview').attr('src', e.target.result).show();
                $('#uploadPlaceholder').hide();
                $('#photoFileName').text('✓ ' + file.name).show();
            };
            reader.readAsDataURL(file);
        }
    }

    function renderChildren(count) {
        const container = document.getElementById('childrenContainer');
        if (count === 0) { container.innerHTML = ''; return; }
        let html = '';
        for (let i = 0; i < count; i++) {
            const c = childData[i] || {};
            html += `
            <div class="card mb-2 p-3 bg-light border-0 shadow-sm">
                <div class="fw-semibold mb-2 small text-secondary">ລູກຄົນທີ ${i + 1}</div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="children[${i}][first_name]"
                               placeholder="ຊື່" value="${c.first_name || ''}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="children[${i}][last_name]"
                               placeholder="ນາມສະກຸນ" value="${c.last_name || ''}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="children[${i}][dob]"
                               value="${c.dob || ''}">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="children[${i}][gender]">
                            <option value="">ເພດ</option>
                            <option value="male"   ${c.gender === 'male'   ? 'selected' : ''}>ຊາຍ</option>
                            <option value="female" ${c.gender === 'female' ? 'selected' : ''}>ຍິງ</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="children[${i}][note]"
                               placeholder="ໝາຍເຫດ" value="${c.note || ''}">
                    </div>
                </div>
            </div>`;
        }
        container.innerHTML = html;
    }

$(document).ready(function () {

    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        language: {
            noResults:  () => 'ບໍ່ພົບຂໍ້ມູນ',
            searching:  () => 'ກຳລັງຄົ້ນຫາ...',
        }
    });

    $('#birthProvince').on('change', function () {
        loadDistricts($(this).val(), '#birthDistrict');
    });
    $('#currentProvince').on('change', function () {
        loadDistricts($(this).val(), '#currentDistrict');
    });

    // Pre-load districts from saved employee data
    @php
        $bProvince = old('birth_province_id',   $employee->birth_province_id);
        $bDistrict = old('birth_district_id',   $employee->birth_district_id);
        $cProvince = old('current_province_id', $employee->current_province_id);
        $cDistrict = old('current_district_id', $employee->current_district_id);
    @endphp
    @if($bProvince)
        loadDistricts('{{ $bProvince }}', '#birthDistrict', '{{ $bDistrict }}');
    @endif
    @if($cProvince)
        loadDistricts('{{ $cProvince }}', '#currentDistrict', '{{ $cDistrict }}');
    @endif

    // Render children rows
    const initialCount = parseInt("{{ old('child_count', $employee->children->count()) }}") || 0;
    renderChildren(initialCount);

    document.getElementById('childCount').addEventListener('input', function () {
        renderChildren(parseInt(this.value) || 0);
    });

    // Form validation
    document.getElementById('employeeForm').addEventListener('submit', function (e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('was-validated');
            Swal.fire({
                icon: 'warning',
                title: 'ຂໍ້ມູນບໍ່ຄົບຖ້ວນ',
                text: 'ກະລຸນາຕື່ມຂໍ້ມູນທີ່ຈຳເປັນໃຫ້ຄົບ',
                confirmButtonColor: '#6366f1',
            });
        }
    });

    // Scroll spy
    const sections = document.querySelectorAll('[id^="sec"]');
    const pills    = document.querySelectorAll('.step-pill');
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(s => { if (window.scrollY >= s.offsetTop - 130) current = s.id; });
        pills.forEach(p => p.classList.toggle('active', p.getAttribute('href') === `#${current}`));
    });

});
</script>
@endpush
