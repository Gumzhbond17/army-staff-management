@extends('layouts.mainLayout')

@section('content')
    <div class="row mt-4">
        <h4 class="mb-4"><i class="bi bi-person-check me-2"></i>ຄົ້ນຫາຂໍ້ມູນພະນັກງານ</h4>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap py-2 px-3">

                    {{-- Search --}}
                    <form action="{{ route('employees.index') }}" method="GET"
                        class="d-flex align-items-center gap-2">
                        <div class="input-group" style="width: 260px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0"
                                placeholder="ຄົ້ນຫາ..." name="search" value="{{ request('search') }}">
                        </div>
                        <button class="btn btn-outline-primary d-flex align-items-center gap-1" type="submit">
                            <i class="bi bi-search me-1"></i> ຄົ້ນຫາ
                        </button>
                        <a href="{{ route('employees.index') }}"
                            class="btn btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-clockwise me-1"></i> ໂຫຼດຂໍ້ມູນ
                        </a>
                    </form>

                    <button type="button" class="btn btn-success d-flex align-items-center gap-1" onclick="location.href='{{ route('employees.create') }}'">
                        <i class="bi bi-plus-lg me-1"></i> ເພີ່ມຂໍ້ມູນ
                    </button>
                    {{-- <a class="nav-link active" aria-current="page" href="{{ route('employees.create') }}">
                        <i class="bi bi-person-plus me-1"></i>
                        ເພີ່ມຂໍ້ມູນ
                    </a> --}}
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        {{-- ===== THEAD ===== --}}
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>ຮູບ</th>
                                <th>ຊື່ ແລະ ນາມສະກຸນ</th>
                                <th>ລະຫັດນາຍທະຫານ</th>
                                <th>ເພດ</th>
                                <th>ກອງປະຈຳ</th>
                                <th>ສະຖານະ</th>
                                <th>ໝູ່ເລືອດ</th>
                                <th>ວັນເດືອນປີເກີດ</th>
                                <th style="width:120px;">ຈັດການ</th>
                            </tr>
                        </thead>

                        {{-- ===== TBODY ===== --}}
                        <tbody>
                            @forelse ($employees as $employee)
                                <tr>
                                    {{-- ລຳດັບ --}}
                                    <td>
                                        <span class="d-inline-flex align-items-center justify-content-center
                                            rounded-circle bg-light text-secondary"
                                            style="width:26px; height:26px; font-size:12px; font-weight:500;">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    {{-- ຮູບຖ່າຍ --}}
                                    <td>
                                        @if($employee->photo)
                                            <img src="{{ asset('storage/' . $employee->photo) }}"
                                                alt="photo"
                                                class="rounded-circle object-fit-cover"
                                                style="width:36px; height:36px; border:2px solid #e2e8f0;">
                                        @else
                                            <span class="d-inline-flex align-items-center justify-content-center
                                                rounded-circle bg-secondary text-white"
                                                style="width:36px; height:36px; font-size:14px;">
                                                <i class="bi bi-person"></i>
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ຊື່ --}}
                                    <td class="fw-medium">{{ $employee->full_name }}</td>

                                    {{-- ລະຫັດນາຍທະຫານ --}}
                                    <td>
                                        @if($employee->officer_code)
                                            <span class="badge bg-light text-dark border" style="font-family:monospace; font-size:12px;">
                                                {{ $employee->officer_code }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- ເພດ --}}
                                    <td>
                                        @if($employee->gender === 'ຊາຍ')
                                            <span class="text-primary"><i class="bi bi-gender-male"></i> ຊາຍ</span>
                                        @elseif($employee->gender === 'ຍິງ')
                                            <span class="text-danger"><i class="bi bi-gender-female"></i> ຍິງ</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- ກອງປະຈຳ --}}
                                    <td>{{ $employee->unit?->name ?? '—' }}</td>

                                    {{-- ສະຖານະ --}}
                                    <td>
                                        @if($employee->workStatus)
                                            <span class="badge rounded-pill"
                                                style="background:#EAF3DE; color:#27500A; font-weight:500; font-size:12px;">
                                                <span class="rounded-circle d-inline-block me-1"
                                                    style="width:6px; height:6px; background:#3B6D11;"></span>
                                                {{ $employee->workStatus->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- ໝູ່ເລືອດ --}}
                                    <td>
                                        @if($employee->blood_group)
                                            <span class="badge rounded-pill"
                                                style="background:#FCEBEB; color:#501313; font-weight:600; font-size:12px;">
                                                {{ $employee->blood_group }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- ວັນເດືອນປີເກີດ --}}
                                    <td class="text-muted" style="font-size:13px;">
                                        {{ $employee->dob ? \Carbon\Carbon::parse($employee->dob)->format('d/m/Y') : '—' }}
                                    </td>

                                    {{-- ຈັດການ --}}
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('employees.show', $employee->id) }}"
                                            class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-eye" style="font-size:13px;"></i>
                                            </a>
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                            class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-pencil" style="font-size:13px;"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 btn-delete"
                                                data-name="{{ $employee->full_name }}"
                                                data-url="{{ route('employees.destroy', $employee->id) }}">
                                                <i class="bi bi-trash" style="font-size:13px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        ບໍ່ມີຂໍ້ມູນພະນັກງານ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3 mx-3">
                        {{ $employees->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         MODAL — Create / Edit
    ══════════════════════════════════════════ --}}
    {{-- Paste this modal ONCE, just before @push('scripts') --}}
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0"
                    style="background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%); padding:1.25rem 1.5rem;">
                    <div class="d-flex align-items-center gap-3 w-100">
                        <div id="modal-avatar"
                            class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary fw-bold flex-shrink-0"
                            style="width:52px; height:52px; font-size:18px; overflow:hidden;">
                        </div>
                        <div class="text-white">
                            <h6 class="mb-0 fw-bold fs-6" id="modal-fullname">—</h6>
                            <small class="opacity-75" id="modal-unit">—</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body p-0" id="modal-body-content">

                    {{-- Loading state --}}
                    <div id="modal-loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted mt-2 mb-0" style="font-size:0.85rem;">ກຳລັງໂຫຼດ...</p>
                    </div>

                    {{-- Content (hidden until loaded) --}}
                    <div id="modal-content" style="display:none;">

                        {{-- Section helper macro via JS template --}}
                        <div style="padding:1.25rem 1.5rem 0;">

                            {{-- I: ຂໍ້ມູນທົ່ວໄປ --}}
                            <p class="text-uppercase fw-semibold mb-2"
                            style="font-size:0.7rem; color:#6366f1; letter-spacing:1px;">
                                I · ຂໍ້ມູນທົ່ວໄປ
                            </p>
                            <div class="row g-2 mb-4">
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#f8fafc;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ເພດ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-gender">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#f8fafc;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ວັນເດືອນປີເກີດ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-dob">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#f8fafc;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ລະຫັດນາຍທະຫານ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem; font-family:monospace;" id="m-officer-code">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#f8fafc;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ໝູ່ເລືອດ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-blood">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#f8fafc;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ລະຫັດບັດປະຈຳຕົວ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem; font-family:monospace;" id="m-idcard">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#f8fafc;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ສະຖານະ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-work-status">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#f8fafc;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ໜ້າທີ່ພັກ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-party-duty">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#f8fafc;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ໜ້າທີ່ບັນຊາ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-command-duty">—</p>
                                    </div>
                                </div>
                            </div>

                            {{-- II & III: ທີ່ຢູ່ --}}
                            <p class="text-uppercase fw-semibold mb-2"
                            style="font-size:0.7rem; color:#0ea5e9; letter-spacing:1px;">
                                II · ສະຖານທີ່ເກີດ &nbsp;/&nbsp; III · ທີ່ຢູ່ປັດຈຸບັນ
                            </p>
                            <div class="row g-2 mb-4">
                                <div class="col-md-6">
                                    <div class="p-2 rounded-3" style="background:#f0f9ff;">
                                        <p class="mb-1 fw-medium" style="font-size:0.7rem; color:#0ea5e9;">ສະຖານທີ່ເກີດ</p>
                                        <p class="mb-0" style="font-size:0.85rem;" id="m-birth-addr">—</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 rounded-3" style="background:#f0fdf4;">
                                        <p class="mb-1 fw-medium" style="font-size:0.7rem; color:#10b981;">ທີ່ຢູ່ປັດຈຸບັນ</p>
                                        <p class="mb-0" style="font-size:0.85rem;" id="m-current-addr">—</p>
                                    </div>
                                </div>
                            </div>

                            {{-- IV: ການສຶກສາ --}}
                            <p class="text-uppercase fw-semibold mb-2"
                            style="font-size:0.7rem; color:#f59e0b; letter-spacing:1px;">
                                IV · ການສຶກສາ
                            </p>
                            <div class="row g-2 mb-4">
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#fffbeb;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ລະດັບວັດທະນະທຳ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-culture">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#fffbeb;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ລະດັບທິດສະດີ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-theory">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#fffbeb;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ຮຽນທິດສະດີຈາກ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-theory-from">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 rounded-3" style="background:#fffbeb;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ລະດັບວິຊາສະເພາະ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-profession">—</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-2 rounded-3" style="background:#fffbeb;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ຮຽນວິຊາສະເພາະຈາກ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-profession-from">—</p>
                                    </div>
                                </div>
                            </div>

                            {{-- V: ສັນຊາດ --}}
                            <p class="text-uppercase fw-semibold mb-2"
                            style="font-size:0.7rem; color:#ec4899; letter-spacing:1px;">
                                V · ສັນຊາດ / ຊົນເຜົ່າ / ຊົນຊັ້ນ
                            </p>
                            <div class="row g-2 mb-4">
                                <div class="col-6 col-md-2">
                                    <div class="p-2 rounded-3" style="background:#fdf2f8;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ສັນຊາດ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-nationality">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="p-2 rounded-3" style="background:#fdf2f8;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ເຊື້ອຊາດ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-ethnicity">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="p-2 rounded-3" style="background:#fdf2f8;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ຊົນເຜົ່າ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-tribe">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="p-2 rounded-3" style="background:#fdf2f8;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ສາດສະໜາ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-religion">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="p-2 rounded-3" style="background:#fdf2f8;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ຊົນຊັ້ນ ກ່ອນ 1975</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-class-before">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="p-2 rounded-3" style="background:#fdf2f8;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ຊົນຊັ້ນ ຫຼັງ 1975</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-class-after">—</p>
                                    </div>
                                </div>
                            </div>

                            {{-- VI: ວັນທີສຳຄັນ --}}
                            <p class="text-uppercase fw-semibold mb-2"
                            style="font-size:0.7rem; color:#f97316; letter-spacing:1px;">
                                VI · ວັນທີສຳຄັນ
                            </p>
                            <div class="row g-2 mb-4">
                                <div class="col-6 col-md-4">
                                    <div class="p-2 rounded-3" style="background:#fff7ed;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ວັນເຂົ້າປະຕິວັດ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-revolution-date">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="p-2 rounded-3" style="background:#fff7ed;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ວັນເຂົ້າທະຫານ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-army-date">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="p-2 rounded-3" style="background:#fff7ed;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ວັນເຂົ້າພັກສຳຮອງ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-candidate-date">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="p-2 rounded-3" style="background:#fff7ed;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ວັນເຂົ້າພັກສົມບູນ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-party-date">—</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="p-2 rounded-3" style="background:#fff7ed;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ວັນໄດ້ຊັ້ນປັດຈຸບັນ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-rank-date">—</p>
                                    </div>
                                </div>
                            </div>

                            {{-- VII: ຄອບຄົວ --}}
                            <p class="text-uppercase fw-semibold mb-2"
                            style="font-size:0.7rem; color:#14b8a6; letter-spacing:1px;">
                                VII · ຄອບຄົວ ແລະ ວິໄນ
                            </p>
                            <div class="row g-2 mb-4">
                                <div class="col-md-5">
                                    <div class="p-2 rounded-3" style="background:#f0fdfa;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ຊື່ພໍ່ແມ່</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-parents">—</p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="p-2 rounded-3" style="background:#f0fdfa;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ຄູ່ຊີວິດ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-spouse">—</p>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="p-2 rounded-3" style="background:#f0fdfa;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ຈຳນວນລູກ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem;" id="m-children">—</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 rounded-3" style="background:#f0fdfa;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ກອງເກົ່າທີ່ເຄີຍສັງກັດ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem; white-space:pre-line;" id="m-prev-units">—</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 rounded-3" style="background:#f0fdfa;">
                                        <p class="mb-0 text-muted" style="font-size:0.7rem;">ປະຫວັດການວິໄນ</p>
                                        <p class="mb-0 fw-medium" style="font-size:0.85rem; white-space:pre-line;" id="m-discipline">—</p>
                                    </div>
                                </div>
                            </div>

                            {{-- VIII: ປະຫວັດ --}}
                            <p class="text-uppercase fw-semibold mb-2"
                            style="font-size:0.7rem; color:#8b5cf6; letter-spacing:1px;">
                                VIII · ປະຫວັດການເຄື່ອນໄຫວ
                            </p>
                            <div class="mb-4">
                                <div class="p-2 rounded-3" style="background:#faf5ff;">
                                    <p class="mb-0" style="font-size:0.85rem; white-space:pre-line; line-height:1.7;" id="m-biography">—</p>
                                </div>
                            </div>

                        </div>{{-- /padding wrapper --}}
                    </div>{{-- /modal-content --}}
                </div>{{-- /modal-body --}}

                {{-- Footer --}}
                <div class="modal-footer border-0 pt-0" style="padding:0.75rem 1.5rem 1.25rem;">
                    <a id="modal-edit-btn" href="#"
                    class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1">
                        <i class="bi bi-pencil" style="font-size:13px;"></i> ແກ້ໄຂ
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1" style="font-size:13px;"></i> ປິດ
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Hidden delete form --}}
    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- ══════════════════════════════════════════
         SCRIPTS — loaded directly in @section
         (avoids @stack timing issues with Vite)
    ══════════════════════════════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function waitForBootstrap(callback) {
            if (typeof bootstrap !== 'undefined') {
                callback();
            } else {
                setTimeout(function () { waitForBootstrap(callback); }, 50);
            }
        }

        waitForBootstrap(function () {

            // ── Refs ─────────────────────────────────────────────────────
            var employeeModalEl = document.getElementById('employeeModal');
            var deleteForm      = document.getElementById('deleteForm');
            var bsModal         = new bootstrap.Modal(employeeModalEl);

            // ── VIEW modal (AJAX JSON fetch) ──────────────────────────────
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-view');
                if (!btn) return;

                var url = btn.getAttribute('data-url');

                // Reset to loading state
                document.getElementById('modal-loading').style.display  = 'block';
                document.getElementById('modal-content').style.display  = 'none';
                document.getElementById('modal-fullname').textContent   = 'ກຳລັງໂຫຼດ...';
                document.getElementById('modal-unit').textContent       = '';
                document.getElementById('modal-avatar').innerHTML       =
                    '<i class="bi bi-person" style="font-size:20px;color:#6366f1;"></i>';

                bsModal.show();

                fetch(url, {
                    headers: { 'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function (res) {
                    if (!res.ok) throw new Error('Network error');
                    return res.json();
                })
                .then(function (emp) {

                    var fmt = function (d) {
                        if (!d) return '—';
                        var parts = d.split('-');
                        return parts[2] + '/' + parts[1] + '/' + parts[0];
                    };
                    var val = function (v) { return v || '—'; };

                    // ── Header ─────────────────────────────────────────
                    document.getElementById('modal-fullname').textContent =
                        val(emp.full_name);
                    document.getElementById('modal-unit').textContent =
                        emp.unit ? emp.unit.name : '—';

                    // Avatar: photo or initials
                    var avatar = document.getElementById('modal-avatar');
                    if (emp.photo) {
                        avatar.innerHTML =
                            '<img src="/storage/' + emp.photo +
                            '" style="width:52px;height:52px;object-fit:cover;border-radius:50%;">';
                    } else {
                        var initials = (emp.full_name || '?')
                            .split(' ').map(function (w) { return w[0]; })
                            .slice(0, 2).join('').toUpperCase();
                        avatar.textContent = initials;
                        avatar.style.fontSize  = '16px';
                        avatar.style.color     = '#6366f1';
                        avatar.style.fontWeight = '600';
                    }

                    // ── Section I ──────────────────────────────────────
                    document.getElementById('m-gender').textContent       = val(emp.gender);
                    document.getElementById('m-dob').textContent          = fmt(emp.dob);
                    document.getElementById('m-officer-code').textContent = val(emp.officer_code);
                    document.getElementById('m-blood').textContent        = val(emp.blood_group);
                    document.getElementById('m-idcard').textContent       = val(emp.id_card_number);
                    document.getElementById('m-work-status').textContent  =
                        emp.work_status ? emp.work_status.name : '—';
                    document.getElementById('m-party-duty').textContent   = val(emp.party_duty);
                    document.getElementById('m-command-duty').textContent = val(emp.command_duty);

                    // ── Section II & III ───────────────────────────────
                    var birthAddr = [
                        emp.birth_village,
                        emp.birth_district  ? emp.birth_district.name  : null,
                        emp.birth_province  ? emp.birth_province.name  : null,
                    ].filter(Boolean).join(', ');

                    var currAddr = [
                        emp.current_village,
                        emp.current_district ? emp.current_district.name : null,
                        emp.current_province ? emp.current_province.name : null,
                    ].filter(Boolean).join(', ');

                    document.getElementById('m-birth-addr').textContent   = birthAddr   || '—';
                    document.getElementById('m-current-addr').textContent = currAddr    || '—';

                    // ── Section IV ─────────────────────────────────────
                    document.getElementById('m-culture').textContent        = val(emp.culture_level);
                    document.getElementById('m-theory').textContent         = val(emp.theory_level);
                    document.getElementById('m-theory-from').textContent    = val(emp.theory_from);
                    document.getElementById('m-profession').textContent     = val(emp.profession_level);
                    document.getElementById('m-profession-from').textContent = val(emp.profession_from);

                    // ── Section V ──────────────────────────────────────
                    document.getElementById('m-nationality').textContent  = val(emp.nationality);
                    document.getElementById('m-ethnicity').textContent    = val(emp.ethnicity_group);
                    document.getElementById('m-tribe').textContent        = val(emp.tribe);
                    document.getElementById('m-religion').textContent     = val(emp.religion);
                    document.getElementById('m-class-before').textContent = val(emp.class_before_1975);
                    document.getElementById('m-class-after').textContent  = val(emp.class_after_1975);

                    // ── Section VI ─────────────────────────────────────
                    document.getElementById('m-revolution-date').textContent = fmt(emp.join_revolution_date);
                    document.getElementById('m-army-date').textContent       = fmt(emp.join_army_date);
                    document.getElementById('m-candidate-date').textContent  = fmt(emp.candidate_party_date);
                    document.getElementById('m-party-date').textContent      = fmt(emp.full_party_date);
                    document.getElementById('m-rank-date').textContent       = fmt(emp.current_rank_date);

                    // ── Section VII ────────────────────────────────────
                    document.getElementById('m-parents').textContent   = val(emp.parents_name);
                    document.getElementById('m-spouse').textContent    = val(emp.spouse_name);
                    document.getElementById('m-children').textContent  =
                        emp.children_count !== null ? emp.children_count : '—';
                    document.getElementById('m-prev-units').textContent = val(emp.previous_units);
                    document.getElementById('m-discipline').textContent = val(emp.discipline_record);

                    // ── Section VIII ───────────────────────────────────
                    document.getElementById('m-biography').textContent = val(emp.biography);

                    // ── Edit button ────────────────────────────────────
                    document.getElementById('modal-edit-btn').href = '/employees/' + emp.id + '/edit';

                    document.getElementById('modal-loading').style.display = 'none';
                    document.getElementById('modal-content').style.display = 'block';
                })
                .catch(function () {
                    document.getElementById('modal-loading').innerHTML =
                        '<p class="text-danger py-4 text-center">ໂຫຼດຂໍ້ມູນບໍ່ສຳເລັດ</p>';
                });
            });

            // ── DELETE confirm ────────────────────────────────────────────
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-delete');
                if (!btn) return;

                var name = btn.getAttribute('data-name');
                var url  = btn.getAttribute('data-url');

                Swal.fire({
                    title:              'ທ່ານຕ້ອງການລຶບລາຍການນີ້ບໍ?',
                    html:               'ລາຍການ: <strong>' + name + '</strong><br><small>ຂໍ້ມູນຈະຖືກລຶບຖາວອນ</small>',
                    icon:               'warning',
                    iconColor:          '#E24B4A',
                    showCancelButton:   true,
                    confirmButtonText:  'ລຶບ',
                    cancelButtonText:   'ຍົກເລີກ',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor:  '#6c757d',
                    reverseButtons:     true,
                }).then(function (result) {
                    if (result.isConfirmed) {
                        deleteForm.action = url;
                        deleteForm.submit();
                    }
                });
            });

            // ── Toast: success ────────────────────────────────────────────
            @if (session('success'))
                Swal.fire({
                    toast:             true,
                    position:          'top-end',
                    icon:              'success',
                    title:             "{{ session('success') }}",
                    showConfirmButton: false,
                    timer:             3000,
                    timerProgressBar:  true,
                });
            @endif

            // ── Toast: error ──────────────────────────────────────────────
            @if (session('error'))
                Swal.fire({
                    toast:             true,
                    position:          'top-end',
                    icon:              'error',
                    title:             "{{ session('error') }}",
                    showConfirmButton: false,
                    timer:             3000,
                    timerProgressBar:  true,
                });
            @endif

        }); // end waitForBootstrap
    </script>

@endsection
