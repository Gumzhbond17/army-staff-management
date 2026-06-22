@extends('layouts.mainLayout')

@section('content')
    <div class="row mt-4">
        <h4 class="mb-4"><i class="bi bi-award me-2"></i>ຈັດການຂໍ້ມູນການເລື່ອນຊັ້ນ</h4>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap py-2 px-3">

                    {{-- Search --}}
                    <form action="{{ route('ranks.index') }}" method="GET"
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
                        <a href="{{ route('ranks.index') }}"
                            class="btn btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-clockwise me-1"></i> ໂຫຼດຂໍ້ມູນ
                        </a>
                    </form>

                    <button type="button" class="btn btn-success d-flex align-items-center gap-1" id="btnCreate">
                        <i class="bi bi-plus-lg me-1"></i> ເພີ່ມຂໍ້ມູນ
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted fw-medium" style="font-size:14px; width:56px;">#</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ຊື່/ລາຍການ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ສະຖານະ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ສ້າງວັນທີ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ແກ້ໄຂວັນທີ</th>
                                <th class="text-muted fw-medium" style="font-size:14px; width:160px;">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ranks as $rank)
                                <tr>
                                    <td>
                                        <span class="d-inline-flex align-items-center justify-content-center
                                            rounded-circle bg-light text-secondary"
                                            style="width:26px; height:26px; font-size:12px; font-weight:500;">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="fw-medium">{{ $rank->name }}</td>
                                    <td>
                                        <span class="badge rounded-pill d-inline-flex align-items-center gap-1"
                                            style="background-color: {{ $rank->is_active ? '#EAF3DE' : '#FCEBEB' }};
                                                   color: {{ $rank->is_active ? '#27500A' : '#501313' }};
                                                   font-weight:500; font-size:12px;">
                                            <span class="rounded-circle"
                                                style="width:6px; height:6px; display:inline-block;
                                                       background: {{ $rank->is_active ? '#3B6D11' : '#A32D2D' }};"></span>
                                            {{ $rank->is_active ? 'ໃຊ້ງານ' : 'ບໍ່ໃຊ້ງານ' }}
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size:13px; font-family:monospace;">
                                        {{ $rank->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="text-muted" style="font-size:13px; font-family:monospace;">
                                        {{ $rank->updated_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1 btn-edit"
                                                data-id="{{ $rank->id }}"
                                                data-name="{{ $rank->name }}"
                                                data-active="{{ $rank->is_active ? '1' : '0' }}"
                                                data-url="{{ route('ranks.update', $rank->id) }}">
                                                <i class="bi bi-pencil" style="font-size:13px;"></i> ແກ້ໄຂ
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 btn-delete"
                                                data-name="{{ $rank->name }}"
                                                data-url="{{ route('ranks.destroy', $rank->id) }}">
                                                <i class="bi bi-trash" style="font-size:13px;"></i> ລຶບ
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        ບໍ່ມີຂໍ້ມູນ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3 mx-3">
                        {{ $ranks->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         MODAL — Create / Edit
    ══════════════════════════════════════════ --}}
    <div class="modal fade" id="rankModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="rankForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="rankModalLabel"></h5>
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
    <script>
        // Wait for Bootstrap to be ready (Vite may load it async)
        function waitForBootstrap(callback) {
            if (typeof bootstrap !== 'undefined') {
                callback();
            } else {
                setTimeout(function () { waitForBootstrap(callback); }, 50);
            }
        }

        waitForBootstrap(function () {

            // ── Refs ─────────────────────────────────────────────────
            var modalEl     = document.getElementById('rankModal');
            var rankForm    = document.getElementById('rankForm');
            var formMethod  = document.getElementById('formMethod');
            var inputName   = document.getElementById('inputName');
            var inputActive = document.getElementById('inputIsActive');
            var labelTitle  = document.getElementById('rankModalLabel');
            var deleteForm  = document.getElementById('deleteForm');
            var bsModal     = new bootstrap.Modal(modalEl);

            // ── Open CREATE ───────────────────────────────────────────
            document.getElementById('btnCreate').addEventListener('click', function () {
                labelTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>ເພີ່ມຂໍ້ມູນ';
                rankForm.action      = "{{ route('ranks.store') }}";
                formMethod.value     = 'POST';
                inputName.value      = '';
                inputActive.checked  = true;
                bsModal.show();
            });

            // ── Open EDIT (event delegation — works with pagination) ──
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-edit');
                if (!btn) return;

                labelTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>ແກ້ໄຂຂໍ້ມູນ';
                rankForm.action      = btn.getAttribute('data-url');
                formMethod.value     = 'PUT';
                inputName.value      = btn.getAttribute('data-name');
                inputActive.checked  = btn.getAttribute('data-active') === '1';
                bsModal.show();
            });

            // ── DELETE confirm ────────────────────────────────────────
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-delete');
                if (!btn) return;

                var name = btn.getAttribute('data-name');
                var url  = btn.getAttribute('data-url');

                Swal.fire({
                    title:             'ທ່ານຕ້ອງການລຶບລາຍການນີ້ບໍ?',
                    html:              'ລາຍການ: <strong>' + name + '</strong><br><small>ຂໍ້ມູນຈະຖືກລຶບຖາວອນ</small>',
                    icon:              'warning',
                    iconColor:         '#E24B4A',
                    showCancelButton:  true,
                    confirmButtonText: 'ລຶບ',
                    cancelButtonText:  'ຍົກເລີກ',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor:  '#6c757d',
                    reverseButtons:    true,
                }).then(function (result) {
                    if (result.isConfirmed) {
                        deleteForm.action = url;
                        deleteForm.submit();
                    }
                });
            });

            // ── Re-open modal on validation error ─────────────────────
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
                bsModal.show();
            @endif

            // ── Toast: success ────────────────────────────────────────
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

            // ── Toast: error ──────────────────────────────────────────
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
