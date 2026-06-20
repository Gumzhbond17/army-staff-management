@extends('layouts.mainLayout')

@section('content')
    <div class="row mt-4">
        <h4 class="mb-4"><i class="bi bi-person-gear me-2"></i>ຈັດການຂໍ້ມູນຜຸ້ໃຊ້ລະບົບ</h4>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap py-2 px-3">

                    {{-- Search --}}
                    <form action="{{ route('users.index') }}" method="GET"
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
                        <a href="{{ route('users.index') }}"
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
                                <th class="text-muted fw-medium" style="font-size:14px;">ຊື່ຜູ້ໃຊ້</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ອີເມວ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ສິດນຳໃຊ້</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ສະຖານະ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ສ້າງວັນທີ</th>
                                <th class="text-muted fw-medium" style="font-size:14px;">ແກ້ໄຂວັນທີ</th>
                                <th class="text-muted fw-medium" style="font-size:14px; width:220px;">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <span class="d-inline-flex align-items-center justify-content-center
                                            rounded-circle bg-light text-secondary"
                                            style="width:26px; height:26px; font-size:12px; font-weight:500;">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="fw-medium">{{ $user->name }}</td>
                                    <td class="fw-medium">{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ $user->role === 'admin' ? 'ແອັດມິນ' : 'ທົ່ວໄປ' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill d-inline-flex align-items-center gap-1"
                                            style="background-color: {{ $user->is_active ? '#EAF3DE' : '#FCEBEB' }};
                                                   color: {{ $user->is_active ? '#27500A' : '#501313' }};
                                                   font-weight:500; font-size:12px;">
                                            <span class="rounded-circle"
                                                style="width:6px; height:6px; display:inline-block;
                                                       background: {{ $user->is_active ? '#3B6D11' : '#A32D2D' }};"></span>
                                            {{ $user->is_active ? 'ໃຊ້ງານ' : 'ບໍ່ໃຊ້ງານ' }}
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size:13px; font-family:monospace;">
                                        {{ $user->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="text-muted" style="font-size:13px; font-family:monospace;">
                                        {{ $user->updated_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1 btn-edit"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}"
                                                data-active="{{ $user->is_active ? '1' : '0' }}"
                                                data-url="{{ route('users.update', $user->id) }}">
                                                <i class="bi bi-pencil" style="font-size:13px;"></i> ແກ້ໄຂ
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-warning d-inline-flex align-items-center gap-1 btn-reset-pw"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-url="{{ route('users.reset-password', $user->id) }}">
                                                <i class="bi bi-key" style="font-size:13px;"></i> ຣີເຊັດ
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 btn-delete"
                                                data-name="{{ $user->name }}"
                                                data-url="{{ route('users.destroy', $user->id) }}">
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
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         MODAL — Create / Edit
    ══════════════════════════════════════════ --}}
    <div class="modal fade" id="unitModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="unitForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="user_id" id="inputUserId" value="">

                    <div class="modal-header">
                        <h5 class="modal-title" id="unitModalLabel"></h5>
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
                                ຊື່ຜູ້ໃຊ້ <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="name"
                                id="inputName" placeholder="ປ້ອນຊື່ຜູ້ໃຊ້..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                ອີເມວ <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" name="email"
                                id="inputEmail" placeholder="ປ້ອນອີເມວ..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                ລະຫັດຜ່ານ
                                <span class="text-danger d-none" id="passwordRequired">*</span>
                            </label>
                            <input type="password" class="form-control" name="password"
                                id="inputPassword" placeholder="ປ້ອນລະຫັດຜ່ານ...">
                            <small class="text-muted" id="passwordHint">
                                ປະໄວ້ຫວ່າງ ຖ້າບໍ່ຕ້ອງການປ່ຽນລະຫັດຜ່ານ
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                ສິດນຳໃຊ້ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="role" id="inputRole" required>
                                <option value="normal">ທົ່ວໄປ</option>
                                <option value="admin">ແອັດມິນ</option>
                            </select>
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
         MODAL — Reset Password
    ══════════════════════════════════════════ --}}
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content">
                <form id="resetPasswordForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="reset_user_id" id="inputResetUserId">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-key me-2"></i>ຣີເຊັດລະຫັດຜ່ານ
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any() && old('reset_user_id'))
                            <div class="alert alert-danger py-2">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li style="font-size:13px;">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <p class="text-muted mb-3" style="font-size:13px;">
                            ຜູ້ໃຊ້: <strong id="resetUserNameDisplay"></strong>
                        </p>

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                ລະຫັດຜ່ານໃໝ່ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="new_password"
                                    id="inputNewPassword" placeholder="ປ້ອນລະຫັດຜ່ານໃໝ່..." required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" id="toggleNewPw">
                                    <i class="bi bi-eye" id="iconNewPw"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-medium">
                                ຢືນຢັນລະຫັດຜ່ານ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="new_password_confirmation"
                                    id="inputNewPasswordConfirm" placeholder="ຢືນຢັນລະຫັດຜ່ານ..." required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPw">
                                    <i class="bi bi-eye" id="iconConfirmPw"></i>
                                </button>
                            </div>
                            <div id="pwMatchError" class="text-danger mt-1" style="font-size:12px; display:none;">
                                ລະຫັດຜ່ານບໍ່ຕົງກັນ
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
                        </button>
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="bi bi-key me-1"></i> ຣີເຊັດລະຫັດ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

            var modalEl          = document.getElementById('unitModal');
            var unitForm         = document.getElementById('unitForm');
            var formMethod       = document.getElementById('formMethod');
            var inputUserId      = document.getElementById('inputUserId');
            var inputName        = document.getElementById('inputName');
            var inputEmail       = document.getElementById('inputEmail');
            var inputPassword    = document.getElementById('inputPassword');
            var inputRole        = document.getElementById('inputRole');
            var inputActive      = document.getElementById('inputIsActive');
            var passwordRequired = document.getElementById('passwordRequired');
            var passwordHint     = document.getElementById('passwordHint');
            var labelTitle       = document.getElementById('unitModalLabel');
            var deleteForm       = document.getElementById('deleteForm');
            var bsModal          = new bootstrap.Modal(modalEl);

            // ── Open CREATE ───────────────────────────────────────────
            document.getElementById('btnCreate').addEventListener('click', function () {
                labelTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>ເພີ່ມຂໍ້ມູນຜູ້ໃຊ້';
                unitForm.action      = "{{ route('users.store') }}";
                formMethod.value     = 'POST';
                inputUserId.value    = '';

                inputName.value      = '';
                inputEmail.value     = '';
                inputPassword.value  = '';
                inputPassword.required = true;
                passwordRequired.classList.remove('d-none');
                passwordHint.classList.add('d-none');
                inputRole.value      = 'normal';
                inputActive.checked  = true;

                bsModal.show();
            });

            // ── Open EDIT (event delegation — works with pagination) ──
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-edit');
                if (!btn) return;

                labelTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້';
                unitForm.action      = btn.getAttribute('data-url');
                formMethod.value     = 'PUT';
                inputUserId.value    = btn.getAttribute('data-id');

                inputName.value      = btn.getAttribute('data-name');
                inputEmail.value     = btn.getAttribute('data-email');
                inputPassword.value  = '';
                inputPassword.required = false;
                passwordRequired.classList.add('d-none');
                passwordHint.classList.remove('d-none');
                inputRole.value      = btn.getAttribute('data-role');
                inputActive.checked  = btn.getAttribute('data-active') === '1';

                bsModal.show();
            });

            // ── RESET PASSWORD ────────────────────────────────────────
            var resetPwModalEl  = document.getElementById('resetPasswordModal');
            var resetPwForm     = document.getElementById('resetPasswordForm');
            var resetUserName   = document.getElementById('resetUserNameDisplay');
            var inputNewPw      = document.getElementById('inputNewPassword');
            var inputNewPwConf  = document.getElementById('inputNewPasswordConfirm');
            var pwMatchError    = document.getElementById('pwMatchError');
            var bsResetModal    = new bootstrap.Modal(resetPwModalEl);

            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-reset-pw');
                if (!btn) return;

                resetUserName.textContent       = btn.getAttribute('data-name');
                resetPwForm.action              = btn.getAttribute('data-url');
                document.getElementById('inputResetUserId').value = btn.getAttribute('data-id');
                inputNewPw.value                = '';
                inputNewPwConf.value            = '';
                pwMatchError.style.display      = 'none';

                bsResetModal.show();
            });

            // Password visibility toggles
            document.getElementById('toggleNewPw').addEventListener('click', function () {
                var icon = document.getElementById('iconNewPw');
                if (inputNewPw.type === 'password') {
                    inputNewPw.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    inputNewPw.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
            document.getElementById('toggleConfirmPw').addEventListener('click', function () {
                var icon = document.getElementById('iconConfirmPw');
                if (inputNewPwConf.type === 'password') {
                    inputNewPwConf.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    inputNewPwConf.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });

            // Client-side password match check
            resetPwForm.addEventListener('submit', function (e) {
                if (inputNewPw.value !== inputNewPwConf.value) {
                    e.preventDefault();
                    pwMatchError.style.display = 'block';
                    inputNewPwConf.focus();
                } else {
                    pwMatchError.style.display = 'none';
                }
            });
            inputNewPwConf.addEventListener('input', function () {
                pwMatchError.style.display = (inputNewPw.value !== inputNewPwConf.value && inputNewPwConf.value.length > 0)
                    ? 'block' : 'none';
            });

            // Re-open reset modal on server validation error
            @if ($errors->any() && old('reset_user_id'))
                resetUserName.textContent  = "{{ old('reset_user_id') }}";
                resetPwForm.action         = "{{ old('reset_user_id') ? route('users.reset-password', old('reset_user_id')) : '' }}";
                document.getElementById('inputResetUserId').value = "{{ old('reset_user_id') }}";
                bsResetModal.show();
            @endif

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
            @if ($errors->any() && !old('reset_user_id'))
                @if (old('_method') === 'PUT')
                    labelTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້';
                    formMethod.value     = 'PUT';
                    inputUserId.value    = "{{ old('user_id') }}";
                    unitForm.action      = "{{ old('user_id') ? route('users.update', old('user_id')) : '' }}";
                    passwordRequired.classList.add('d-none');
                    passwordHint.classList.remove('d-none');
                @else
                    labelTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>ເພີ່ມຂໍ້ມູນຜູ້ໃຊ້';
                    formMethod.value     = 'POST';
                    inputUserId.value    = '';
                    unitForm.action      = "{{ route('users.store') }}";
                    passwordRequired.classList.remove('d-none');
                    passwordHint.classList.add('d-none');
                @endif
                inputName.value     = "{{ old('name') }}";
                inputEmail.value    = "{{ old('email') }}";
                inputRole.value     = "{{ old('role', 'normal') }}";
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
