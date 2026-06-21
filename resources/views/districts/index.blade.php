@extends('layouts.mainLayout')

@section('content')
    <div class="row mt-4">
        <h4 class="mb-4"><i class="bi bi-geo me-2"></i>ຈັດການຂໍ້ມູນເມືອງ</h4>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap py-2 px-3">

                    {{-- Search --}}
                    <form action="{{ route('districts.index') }}" method="GET"
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
                        <a href="{{ route('districts.index') }}"
                            class="btn btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-clockwise me-1"></i> ໂຫຼດຂໍ້ມູນ
                        </a>
                    </form>

                    <button type="button" class="btn btn-success d-flex align-items-center gap-1" id="btnCreate">
                        <i class="bi bi-plus-lg me-1"></i> ເພີ່ມຂໍ້ມູນ
                    </button>
                    {{-- <button type="button"
                        class="btn btn-success d-flex align-items-center gap-1"
                        id="btnCreate"
                        onclick="openCreate()">
                        <i class="bi bi-plus-lg me-1"></i> ເພີ່ມຂໍ້ມູນ
                    </button> --}}
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted fw-medium" style="font-size:14px; width:56px;">#</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ລະຫັດແຂວງ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ຊື່ແຂວງ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ຊື່/ລາຍການ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ສະຖານະ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ສ້າງວັນທີ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ແກ້ໄຂວັນທີ</th>
                                <th class="text-muted fw-medium" style="font-size:14px; width:160px;">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($districts as $district)
                                <tr>
                                    <td>
                                        <span class="d-inline-flex align-items-center justify-content-center
                                            rounded-circle bg-light text-secondary"
                                            style="width:26px; height:26px; font-size:12px; font-weight:500;">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size:13px; font-family:monospace;">{{ $district->province->code }}</td>
                                    <td class="fw-medium">{{ $district->province->name }}</td>
                                    <td class="fw-medium">{{ $district->name }}</td>
                                    <td>
                                        <span class="badge rounded-pill d-inline-flex align-items-center gap-1"
                                            style="background-color: {{ $district->is_active ? '#EAF3DE' : '#FCEBEB' }};
                                                   color: {{ $district->is_active ? '#27500A' : '#501313' }};
                                                   font-weight:500; font-size:12px;">
                                            <span class="rounded-circle"
                                                style="width:6px; height:6px; display:inline-block;
                                                       background: {{ $district->is_active ? '#3B6D11' : '#A32D2D' }};"></span>
                                            {{ $district->is_active ? 'ໃຊ້ງານ' : 'ບໍ່ໃຊ້ງານ' }}
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size:13px; font-family:monospace;">
                                        {{ $district->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="text-muted" style="font-size:13px; font-family:monospace;">
                                        {{ $district->updated_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1 btn-edit"
                                                data-id="{{ $district->id }}"
                                                data-province-id="{{ $district->province_id }}"
                                                data-name="{{ $district->name }}"
                                                data-active="{{ $district->is_active ? '1' : '0' }}"
                                                data-url="{{ route('districts.update', $district->id) }}">
                                                <i class="bi bi-pencil" style="font-size:13px;"></i> ແກ້ໄຂ
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 btn-delete"
                                                data-name="{{ $district->name }}"
                                                data-url="{{ route('districts.destroy', $district->id) }}">
                                                <i class="bi bi-trash" style="font-size:13px;"></i> ລຶບ
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        ບໍ່ມີຂໍ້ມູນ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3 mx-3">
                        {{ $districts->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         MODAL — Create / Edit
    ══════════════════════════════════════════ --}}
    <div class="modal fade" id="district" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="districtForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="districtLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger py-2">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li style="font-size:13px;">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                ແຂວງ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select select2-province" name="province_id" id="inputProvinceId" required>
                                <option value="">-- ເລືອກແຂວງ --</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->code }} - {{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                ຊື່ / ລາຍການ <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="name"
                                id="inputName" placeholder="ປ້ອນຊື່ລາຍການ..." required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-medium">ສະຖານະ</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    name="is_active" id="inputIsActive" value="1" checked>
                                <label class="form-check-label" for="inputIsActive">ໃຊ້ງານ</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-floppy me-1"></i> ບັນທຶກ
                        </button>
                    </div>
                </form>
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
document.addEventListener('DOMContentLoaded', function () {

    // ── Refs ──────────────────────────────────────────────────────
    const districtForm = document.getElementById('districtForm');
    const formMethod   = document.getElementById('formMethod');
    const inputName    = document.getElementById('inputName');
    const inputActive  = document.getElementById('inputIsActive');
    const labelTitle   = document.getElementById('districtLabel');
    const deleteForm   = document.getElementById('deleteForm');

    function getModal() {
        return bootstrap.Modal.getOrCreateInstance(document.getElementById('district'));
    }

    // ── Select2 ───────────────────────────────────────────────────
    $('#district').on('shown.bs.modal', function () {
        $('.select2-province').select2({
            theme:          'bootstrap-5',
            placeholder:    '-- ເລືອກແຂວງ --',
            allowClear:     true,
            dropdownParent: $('#district'),
        });
    });

    // ── Open CREATE ───────────────────────────────────────────────
    document.getElementById('btnCreate').addEventListener('click', function () {
        labelTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>ເພີ່ມຂໍ້ມູນ';
        districtForm.action  = "{{ route('districts.store') }}";
        formMethod.value     = 'POST';
        inputName.value      = '';
        inputActive.checked  = true;
        $('#inputProvinceId').val('').trigger('change');
        getModal().show();
    });

    // ── Open EDIT ─────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-edit');
        if (!btn) return;

        labelTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>ແກ້ໄຂຂໍ້ມູນ';
        districtForm.action  = btn.getAttribute('data-url');
        formMethod.value     = 'PUT';
        inputName.value      = btn.getAttribute('data-name');
        inputActive.checked  = btn.getAttribute('data-active') === '1';
        $('#inputProvinceId').val(btn.getAttribute('data-province-id')).trigger('change');
        getModal().show();
    });

    // ── DELETE confirm ────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-delete');
        if (!btn) return;

        Swal.fire({
            title:              'ທ່ານຕ້ອງການລຶບລາຍການນີ້ບໍ?',
            html:               'ລາຍການ: <strong>' + btn.getAttribute('data-name') + '</strong><br><small>ຂໍ້ມູນຈະຖືກລຶບຖາວອນ</small>',
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
                deleteForm.action = btn.getAttribute('data-url');
                deleteForm.submit();
            }
        });
    });

    // ── Re-open modal on validation error ────────────────────────
    @if ($errors->any())
        @if (old('_method') === 'PUT')
            labelTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>ແກ້ໄຂຂໍ້ມູນ';
            formMethod.value     = 'PUT';
        @else
            labelTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>ເພີ່ມຂໍ້ມູນ';
            formMethod.value     = 'POST';
        @endif
        inputName.value     = "{{ old('name') }}";
        inputActive.checked = "{{ old('is_active', '1') }}" === '1';
        $('#inputProvinceId').val("{{ old('province_id') }}").trigger('change');
        getModal().show();
    @endif

    // ── Toast: success ────────────────────────────────────────────
    @if (session('success'))
        Swal.fire({
            toast: true, position: 'top-end', icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false, timer: 3000, timerProgressBar: true,
        });
    @endif

    // ── Toast: error ──────────────────────────────────────────────
    @if (session('error'))
        Swal.fire({
            toast: true, position: 'top-end', icon: 'error',
            title: "{{ session('error') }}",
            showConfirmButton: false, timer: 3000, timerProgressBar: true,
        });
    @endif

});
</script>

@endsection
