@extends('layouts.mainLayout')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 30%, #e0e7ff 60%, #f5f3ff 100%) !important;
        background-attachment: fixed !important;
    }
    body::before {
        content: ''; position: fixed; top: -150px; left: -150px;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%);
        border-radius: 50%; pointer-events: none; z-index: 0;
    }
    body::after {
        content: ''; position: fixed; bottom: -150px; right: -100px;
        width: 450px; height: 450px;
        background: radial-gradient(circle, rgba(14,165,233,0.15) 0%, transparent 70%);
        border-radius: 50%; pointer-events: none; z-index: 0;
    }

    .page-wrapper { position: relative; z-index: 1; padding: 1.5rem 0 4rem; }

    .page-header {
        background: rgba(255,255,255,0.55);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.75);
        border-radius: 20px; padding: 1.25rem 1.75rem; margin-bottom: 1.5rem;
        box-shadow: 0 4px 24px rgba(99,102,241,0.08);
    }
    .page-header h2 { font-size: 1.4rem; font-weight: 700; color: #3730a3; margin: 0; }
    .page-header p  { color: #64748b; margin: 0.2rem 0 0; font-size: 0.85rem; }

    .glass-card {
        background: rgba(255,255,255,0.60);
        backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,0.80);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.10), 0 1px 0 rgba(255,255,255,0.9) inset;
        overflow: hidden; margin-bottom: 1.5rem;
    }
    .section-header {
        background: linear-gradient(90deg, rgba(99,102,241,0.10) 0%, rgba(14,165,233,0.06) 100%);
        border-bottom: 1px solid rgba(99,102,241,0.12);
        padding: 0.85rem 1.5rem; display: flex; align-items: center; gap: 0.6rem;
    }
    .section-icon {
        width: 30px; height: 30px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 8px; display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 0.8rem; flex-shrink: 0;
    }
    .section-header h6 { font-weight: 600; color: #3730a3; margin: 0; font-size: 0.875rem; }
    .card-body-inner { padding: 1.5rem; }

    /* Drop zone */
    .drop-zone {
        border: 2.5px dashed rgba(99,102,241,0.40);
        border-radius: 16px;
        background: rgba(99,102,241,0.03);
        padding: 3rem 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
    }
    .drop-zone.dragover { border-color: #6366f1; background: rgba(99,102,241,0.08); }
    .drop-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .drop-icon {
        width: 64px; height: 64px;
        background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(139,92,246,0.12));
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; font-size: 1.75rem; color: #6366f1;
    }
    .drop-zone.has-file { border-color: #22c55e; background: rgba(34,197,94,0.04); }
    .drop-zone.has-file .drop-icon { background: linear-gradient(135deg, rgba(34,197,94,0.15), rgba(16,185,129,0.15)); color: #16a34a; }

    /* Buttons */
    .btn-upload {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border: none; border-radius: 10px; color: #fff; font-weight: 600;
        font-size: 0.875rem; padding: 0.6rem 1.75rem;
        box-shadow: 0 4px 15px rgba(99,102,241,0.35); transition: all 0.25s ease;
    }
    .btn-upload:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); color: #fff; }
    .btn-upload:disabled { opacity: 0.55; transform: none; cursor: not-allowed; }
    .btn-template {
        background: rgba(255,255,255,0.7); border: 1.5px solid rgba(148,163,184,0.4);
        border-radius: 10px; color: #64748b; font-weight: 500;
        font-size: 0.875rem; padding: 0.6rem 1.25rem; transition: all 0.25s ease;
    }
    .btn-template:hover { background: rgba(255,255,255,0.9); border-color: #6366f1; color: #6366f1; }
    .btn-submit {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none; border-radius: 10px; color: #fff; font-weight: 600;
        font-size: 0.875rem; padding: 0.6rem 1.75rem;
        box-shadow: 0 4px 15px rgba(16,185,129,0.35); transition: all 0.25s ease;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(16,185,129,0.45); color: #fff; }
    .btn-reset-upload {
        background: rgba(239,68,68,0.07); border: 1.5px solid rgba(239,68,68,0.25);
        border-radius: 10px; color: #ef4444; font-weight: 500;
        font-size: 0.875rem; padding: 0.6rem 1.25rem; transition: all 0.25s ease;
    }
    .btn-reset-upload:hover { background: rgba(239,68,68,0.14); border-color: #ef4444; color: #dc2626; }

    /* Preview table */
    #previewSection { display: none; }
    .preview-table-wrap { overflow-x: auto; max-height: 420px; overflow-y: auto; border-radius: 12px; border: 1px solid rgba(99,102,241,0.12); }
    .preview-table { font-size: 0.8rem; margin: 0; }
    .preview-table thead th {
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        color: #fff; font-weight: 600; white-space: nowrap;
        padding: 0.55rem 0.9rem; border: none; position: sticky; top: 0;
    }
    .preview-table tbody tr:hover td { background: rgba(99,102,241,0.04); }
    .preview-table tbody td { padding: 0.45rem 0.9rem; border-color: rgba(99,102,241,0.08); white-space: nowrap; }
    .preview-table tbody tr:nth-child(even) td { background: rgba(255,255,255,0.5); }

    /* Badge */
    .count-badge {
        background: rgba(99,102,241,0.12); color: #4338ca;
        font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.65rem;
        border-radius: 50px; margin-left: 0.5rem;
    }

    /* Spinner */
    .spinner-wrap { display: none; align-items: center; gap: 0.6rem; color: #6366f1; font-size: 0.875rem; }
    .spinner-wrap.show { display: flex; }
</style>
@endpush

@section('content')
<div class="page-wrapper">

    {{-- Page Header --}}
    <div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h2><i class="bi bi-file-earmark-arrow-up me-2"></i>ນຳເຂົ້າຂໍ້ມູນພະນັກງານ</h2>
            <p>ອັບໂຫຼດໄຟລ໌ CSV ຫຼື Excel ເພື່ອນຳເຂົ້າຂໍ້ມູນພະນັກງານເຂົ້າສູ່ລະບົບ</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn-template btn">
            <i class="bi bi-arrow-left me-1"></i> ກັບຄືນ
        </a>
    </div>

    {{-- ── Upload Card ── --}}
    <div class="glass-card">
        <div class="section-header">
            <div class="section-icon"><i class="bi bi-cloud-upload"></i></div>
            <h6>ອັບໂຫຼດຟາຍຂໍ້ມູນ</h6>
        </div>
        <div class="card-body-inner">

            {{-- Drop zone --}}
            <div class="drop-zone mb-4" id="dropZone">
                <input type="file" id="fileInput" accept=".csv,.xls,.xlsx">
                <div class="drop-icon" id="dropIcon">
                    <i class="bi bi-file-earmark-spreadsheet" id="dropIconEl"></i>
                </div>
                <p class="fw-semibold mb-1" id="dropTitle" style="color:#3730a3; font-size:1rem;">ລາກໄຟລ໌ມາວາງທີ່ນີ້ ຫຼື ກົດເພື່ອເລືອກໄຟລ໌</p>
                <p class="text-muted mb-0" id="dropSub" style="font-size:0.8rem;">
                    ຮອງຮັບ: <strong>.csv</strong> · <strong>.xls</strong> · <strong>.xlsx</strong> (Excel 2003–2026) &nbsp;|&nbsp; ຂະໜາດສູງສຸດ 10MB
                </p>
            </div>

            {{-- Actions --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <button type="button" class="btn-upload btn" id="btnUpload" disabled>
                        <i class="bi bi-cloud-upload me-1"></i> ອັບໂຫຼດຟາຍຂໍ້ມູນ
                    </button>
                    <div class="spinner-wrap" id="uploadSpinner">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span>ກຳລັງອ່ານໄຟລ໌...</span>
                    </div>
                </div>
                <a href="{{ route('employees.import.template') }}" class="btn-template btn" title="ດາວໂຫຼດ Template Excel">
                    <i class="bi bi-download me-1"></i> ດາວໂຫຼດ Template
                </a>
            </div>

            {{-- Column guide --}}
            <div class="mt-4 p-3 rounded-3" style="background:rgba(99,102,241,0.05); border:1px solid rgba(99,102,241,0.12);">
                <p class="fw-semibold mb-3" style="font-size:0.8rem; color:#4338ca;">
                    <i class="bi bi-info-circle me-1"></i>ໂຄງສ້າງຄໍລຳ — ທັງໝົດ 38 ຄໍລຳ (ລະບົບຈັບຄູ່ຈາກຊື່ header, ລຳດັບຄໍລຳ<strong> ບໍ່ຈຳເປັນ</strong>ຕ້ອງຕົງ)
                </p>
                @php
                // 38 columns A–AL, NO '#' column — row[0] = full_name
                $groups = [
                    ['label' => 'ຂໍ້ມູນຫຼັກ', 'color' => '#6366f1', 'cols' => [
                        ['A', 'ຊື່ ແລະ ນາມສະກຸນ', true],
                        ['B', 'ເພດ (ຊາຍ/ຍິງ)', false],
                        ['C', 'ວັນເກີດ', false],
                        ['D', 'ລ.ນ.ທ', false],
                        ['E', 'ເລກບັດ', false],
                        ['F', 'ກອງປະຈຳ', true],
                        ['G', 'ສະຖານະ', true],
                        ['H', 'ໜ.ທີ່ພັກ', false],
                        ['I', 'ໜ.ທີ່ບັນຊາ', false],
                        ['J', 'ໝູ່ເລືອດ', false],
                    ]],
                    ['label' => 'ທີ່ຢູ່ເກີດ', 'color' => '#0ea5e9', 'cols' => [
                        ['K', 'ບ້ານເກີດ', false],
                        ['L', 'ເມືອງ/ເຂດເກີດ', false],
                        ['M', 'ແຂວງເກີດ', false],
                    ]],
                    ['label' => 'ທີ່ຢູ່ປັດຈຸບັນ', 'color' => '#0ea5e9', 'cols' => [
                        ['N', 'ບ້ານຢູ່', false],
                        ['O', 'ເມືອງ/ເຂດຢູ່', false],
                        ['P', 'ແຂວງຢູ່', false],
                    ]],
                    ['label' => 'ການສຶກສາ', 'color' => '#8b5cf6', 'cols' => [
                        ['Q', 'ລ.ວັດທະນະທຳ', false],
                        ['R', 'ລ.ທິດສະດີ', false],
                        ['S', 'ຮຽນທ.ຈາກ', false],
                        ['T', 'ລ.ວິຊາສະເພາະ', false],
                        ['U', 'ຮຽນວ.ຈາກ', false],
                    ]],
                    ['label' => 'ຊົນເຜົ່າ / ສາດສະໜາ', 'color' => '#f59e0b', 'cols' => [
                        ['V', 'ສັນຊາດ', false],
                        ['W', 'ເຊື້ອຊາດ', false],
                        ['X', 'ຊົນເຜົ່າ', false],
                        ['Y', 'ສາສະໜາ', false],
                        ['Z', 'ຊ.ກ່ອນ 1975', false],
                        ['AA', 'ຊ.ຫຼັງ 1975', false],
                    ]],
                    ['label' => 'ວັນທີສຳຄັນ', 'color' => '#ef4444', 'cols' => [
                        ['AB', 'ເຂົ້າປະຕິວັດ', false],
                        ['AC', 'ເຂົ້າທະຫານ', false],
                        ['AD', 'ພັກສຳຮອງ', false],
                        ['AE', 'ພັກສົມບູນ', false],
                        ['AF', 'ຊັ້ນປັດຈຸບັນ', false],
                    ]],
                    ['label' => 'ຄອບຄົວ', 'color' => '#10b981', 'cols' => [
                        ['AG', 'ຊື່ພໍ່ແມ່', false],
                        ['AH', 'ຊື່ຄູ່ຊີວິດ', false],
                        ['AI', 'ຈ.ລູກ', false],
                    ]],
                    ['label' => 'ອື່ນໆ', 'color' => '#64748b', 'cols' => [
                        ['AJ', 'ກອງເກົ່າ', false],
                        ['AK', 'ບັນທຶກວິໄນ', false],
                        ['AL', 'ປະຫວັດ', false],
                    ]],
                ];
                @endphp

                @foreach($groups as $group)
                <div class="mb-2">
                    <span class="d-inline-block mb-1 px-2 py-0 rounded-1 fw-semibold"
                          style="font-size:0.68rem; color:#fff; background:{{ $group['color'] }};">
                        {{ $group['label'] }}
                    </span>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($group['cols'] as [$letter, $label, $required])
                        <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2"
                              style="font-size:0.7rem; background:rgba(255,255,255,0.75); border:1px solid rgba(99,102,241,0.13);">
                            <strong style="color:{{ $group['color'] }}; min-width:1.5rem;">{{ $letter }}</strong>
                            <span style="color:#475569;">{{ $label }}</span>
                            @if($required)<span class="text-danger ms-1">*</span>@endif
                        </span>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <p class="mb-0 mt-2 text-muted" style="font-size:0.72rem;">
                    <span class="text-danger">*</span> ຈຳເປັນ &nbsp;|&nbsp;
                    ລຳດັບຄໍລຳ <strong>ບໍ່ຈຳເປັນ</strong>ຕ້ອງຕົງ — ລະບົບອ່ານຈາກຊື່ header &nbsp;|&nbsp;
                    ກອງ ແລະ ສະຖານະຕ້ອງຕົງກັບຊື່ໃນລະບົບ &nbsp;|&nbsp;
                    ວັນທີໃຊ້ຮູບແບບ DD/MM/YYYY &nbsp;|&nbsp;
                    ດາວໂຫຼດ Template ເພື່ອເຫັນໂຄງສ້າງຄົບ
                </p>

                {{-- Reference: valid unit and status names --}}
                <div class="mt-3 d-flex flex-wrap gap-3">
                    @if(isset($workStatuses) && $workStatuses->count())
                    <div style="flex:1; min-width:200px;">
                        <p class="fw-semibold mb-1" style="font-size:0.72rem; color:#4338ca;">
                            <i class="bi bi-person-exclamation me-1"></i>ສະຖານະທີ່ໃຊ້ໄດ້ (ຄໍລຳ G):
                        </p>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($workStatuses as $s)
                            <span class="badge"
                                  style="background:rgba(99,102,241,0.08); color:#3730a3; border:1px solid rgba(99,102,241,0.2); font-size:0.7rem; font-weight:500; padding:0.2rem 0.5rem; border-radius:6px; font-family:inherit;">
                                {{ $s }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(isset($units) && $units->count())
                    <div style="flex:2; min-width:260px;">
                        <p class="fw-semibold mb-1" style="font-size:0.72rem; color:#4338ca;">
                            <i class="bi bi-pin-map me-1"></i>ກອງທີ່ໃຊ້ໄດ້ (ຄໍລຳ F):
                        </p>
                        <div class="d-flex flex-wrap gap-1" style="max-height:80px; overflow-y:auto;">
                            @foreach($units as $u)
                            <span class="badge"
                                  style="background:rgba(14,165,233,0.08); color:#0c4a6e; border:1px solid rgba(14,165,233,0.2); font-size:0.7rem; font-weight:500; padding:0.2rem 0.5rem; border-radius:6px; font-family:inherit;">
                                {{ $u }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Preview Card ── --}}
    <div class="glass-card" id="previewSection">
        <div class="section-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="section-icon" style="background:linear-gradient(135deg,#10b981,#059669)"><i class="bi bi-table"></i></div>
                <h6 class="mb-0">ຕົວຢ່າງຂໍ້ມູນ <span class="count-badge" id="rowCountBadge">0 ແຖວ</span></h6>
            </div>
            <button type="button" class="btn-reset-upload btn btn-sm" id="btnResetUpload">
                <i class="bi bi-arrow-counterclockwise me-1"></i> ອັບໂຫຼດໃໝ່
            </button>
        </div>
        <div class="card-body-inner">

            {{-- Skip warnings --}}
            <div id="skipWarnings" style="display:none;" class="mb-3"></div>

            {{-- Table --}}
            <div class="preview-table-wrap mb-4">
                <table class="table preview-table" id="previewTable">
                    <thead id="previewHead"></thead>
                    <tbody id="previewBody"></tbody>
                </table>
            </div>

            {{-- Submit --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <p class="text-muted mb-0" style="font-size:0.8rem;">
                    <i class="bi bi-info-circle text-primary me-1"></i>
                    ກວດສອບຂໍ້ມູນໃຫ້ຄົບຖ້ວນກ່ອນກົດ <strong>ສົ່ງຂໍ້ມູນ</strong>
                </p>
                <div class="d-flex align-items-center gap-2">
                    <div class="spinner-wrap" id="submitSpinner">
                        <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                        <span>ກຳລັງບັນທຶກ...</span>
                    </div>
                    <button type="button" class="btn-submit btn" id="btnSubmit">
                        <i class="bi bi-send-check me-1"></i> ສົ່ງຂໍ້ມູນ
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const dropZone     = document.getElementById('dropZone');
    const fileInput    = document.getElementById('fileInput');
    const btnUpload    = document.getElementById('btnUpload');
    const btnSubmit    = document.getElementById('btnSubmit');
    const btnReset     = document.getElementById('btnResetUpload');
    const uploadSpinner = document.getElementById('uploadSpinner');
    const submitSpinner = document.getElementById('submitSpinner');
    const previewSection = document.getElementById('previewSection');
    const rowCountBadge  = document.getElementById('rowCountBadge');
    const previewHead    = document.getElementById('previewHead');
    const previewBody    = document.getElementById('previewBody');
    const skipWarnings   = document.getElementById('skipWarnings');
    const dropTitle      = document.getElementById('dropTitle');
    const dropSub        = document.getElementById('dropSub');
    const dropIconEl     = document.getElementById('dropIconEl');
    const dropIcon       = document.getElementById('dropIcon');

    let parsedRows    = [];
    let parsedHeaders = [];
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ── File selection ──────────────────────────────────────────────
    fileInput.addEventListener('change', function () {
        handleFileSelected(this.files[0]);
    });

    function handleFileSelected(file) {
        if (!file) return;
        dropZone.classList.add('has-file');
        dropIconEl.className = 'bi bi-file-earmark-check-fill';
        dropTitle.textContent = file.name;
        dropSub.textContent   = (file.size / 1024).toFixed(1) + ' KB — ກົດ "ອັບໂຫຼດຟາຍຂໍ້ມູນ" ເພື່ອດຳເນີນການ';
        btnUpload.disabled = false;
    }

    // ── Drag & drop ─────────────────────────────────────────────────
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) { fileInput.files = e.dataTransfer.files; handleFileSelected(file); }
    });

    // ── Upload → Preview ────────────────────────────────────────────
    btnUpload.addEventListener('click', function () {
        const file = fileInput.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', csrfToken);

        btnUpload.disabled = true;
        uploadSpinner.classList.add('show');
        previewSection.style.display = 'none';

        fetch('{{ route('employees.import.preview') }}', { method: 'POST', body: formData })
            .then(r => {
                if (!r.ok && r.status === 419) throw new Error('CSRF expired — refresh and try again');
                return r.json();
            })
            .then(data => {
                uploadSpinner.classList.remove('show');
                if (!data.success) {
                    Swal.fire({ icon: 'error', title: 'ອ່ານໄຟລ໌ບໍ່ສຳເລັດ', text: data.message, confirmButtonColor: '#6366f1' });
                    btnUpload.disabled = false;
                    return;
                }

                parsedHeaders = data.headers;
                parsedRows    = data.rows;
                renderPreview(data.headers, data.rows);
                rowCountBadge.textContent = data.count + ' ແຖວ';
                previewSection.style.display = 'block';
                previewSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(err => {
                uploadSpinner.classList.remove('show');
                btnUpload.disabled = false;
                Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: err?.message || 'ບໍ່ສາມາດຕິດຕໍ່ server ໄດ້', confirmButtonColor: '#6366f1' });
            });
    });

    // ── Render table ────────────────────────────────────────────────
    function renderPreview(headers, rows) {
        // Header
        let thHtml = '<tr>';
        thHtml += '<th style="width:40px;">#</th>';
        headers.forEach(h => { thHtml += `<th>${h ?? ''}</th>`; });
        thHtml += '</tr>';
        previewHead.innerHTML = thHtml;

        // Body
        let tbHtml = '';
        rows.forEach((row, i) => {
            tbHtml += `<tr><td class="text-muted">${i + 1}</td>`;
            row.forEach(cell => { tbHtml += `<td>${cell ?? ''}</td>`; });
            tbHtml += '</tr>';
        });
        previewBody.innerHTML = tbHtml || '<tr><td colspan="20" class="text-center text-muted py-3">ບໍ່ມີຂໍ້ມູນ</td></tr>';
    }

    // ── Reset upload ────────────────────────────────────────────────
    btnReset.addEventListener('click', function () {
        fileInput.value = '';
        parsedRows    = [];
        parsedHeaders = [];
        dropZone.classList.remove('has-file', 'dragover');
        dropIconEl.className = 'bi bi-file-earmark-spreadsheet';
        dropTitle.textContent = 'ລາກໄຟລ໌ມາວາງທີ່ນີ້ ຫຼື ກົດເພື່ອເລືອກໄຟລ໌';
        dropSub.innerHTML = 'ຮອງຮັບ: <strong>.csv</strong> · <strong>.xls</strong> · <strong>.xlsx</strong> (Excel 2003–2026) &nbsp;|&nbsp; ຂະໜາດສູງສຸດ 10MB';
        btnUpload.disabled = true;
        previewSection.style.display = 'none';
        skipWarnings.style.display = 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // ── Submit with SweetAlert confirm ──────────────────────────────
    btnSubmit.addEventListener('click', function () {
        if (!parsedRows.length) return;

        Swal.fire({
            title: 'ຢືນຢັນການນຳເຂົ້າ?',
            html: `ທ່ານກຳລັງຈະນຳເຂົ້າຂໍ້ມູນ <strong>${parsedRows.length} ລາຍການ</strong> ເຂົ້າສູ່ລະບົບ<br><small class="text-muted">ຂໍ້ມູນທີ່ຊ້ຳກັນຈະຖືກເພີ່ມໃໝ່ — ກວດສອບກ່ອນຢືນຢັນ</small>`,
            icon: 'question',
            iconColor: '#6366f1',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-send-check me-1"></i> ສົ່ງຂໍ້ມູນ',
            cancelButtonText: 'ຍົກເລີກ',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
        }).then(result => {
            if (!result.isConfirmed) return;

            btnSubmit.disabled = true;
            submitSpinner.classList.add('show');

            fetch('{{ route('employees.import.submit') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ rows: parsedRows, headers: parsedHeaders }),
            })
            .then(r => r.json().catch(() => {
                throw new Error(`Server error (HTTP ${r.status}) — check the Laravel log file`);
            }))
            .then(data => {
                submitSpinner.classList.remove('show');
                btnSubmit.disabled = false;

                if (!data.success) {
                    // Handle Laravel validation errors (422)
                    const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'ເກີດຂໍ້ຜິດພາດ');
                    Swal.fire({ icon: 'error', title: 'ບໍ່ສຳເລັດ', text: msg, confirmButtonColor: '#6366f1' });
                    return;
                }

                // Show skipped rows in the page
                if (data.skipped && data.skipped.length) {
                    let warnHtml = `<div class="alert alert-warning py-2 mb-0" style="font-size:0.78rem;">
                        <strong><i class="bi bi-exclamation-triangle me-1"></i>ລາຍການທີ່ຖືກຂ້າມ (${data.skipped.length}):</strong>
                        <ul class="mb-0 mt-1 ps-3">`;
                    data.skipped.forEach(s => { warnHtml += `<li>${s}</li>`; });
                    warnHtml += '</ul></div>';
                    skipWarnings.innerHTML = warnHtml;
                    skipWarnings.style.display = 'block';
                    skipWarnings.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                // When NOTHING was imported, show error so user sees the skip reasons
                if (data.imported === 0) {
                    const reasons = data.skipped?.slice(0, 5).join('<br>') ?? '';
                    Swal.fire({
                        icon: 'error',
                        title: 'ບໍ່ມີລາຍການຖືກນຳເຂົ້າ!',
                        html: (data.skipped?.length
                            ? `ທຸກ <strong>${data.skipped.length}</strong> ລາຍການຖືກຂ້າມ:<br><small style="text-align:left;display:block;margin-top:6px;">${reasons}</small>` + (data.skipped.length > 5 ? `<small>…ແລະ ${data.skipped.length - 5} ລາຍການອີກ (ເບິ່ງລາຍລະອຽດດ້ານລຸ່ມ)</small>` : '')
                            : 'ໄຟລ໌ວ່າງ ຫຼື ຂໍ້ມູນຖືກຂ້າມທັງໝົດ'),
                        confirmButtonColor: '#6366f1',
                        confirmButtonText: 'ຕົກລົງ',
                    });
                    return;
                }

                Swal.fire({
                    icon: data.skipped?.length ? 'warning' : 'success',
                    title: 'ນຳເຂົ້າສຳເລັດ!',
                    html: `ນຳເຂົ້າ <strong>${data.imported}</strong> ລາຍການ`
                        + (data.skipped?.length ? `<br><span class="text-warning">ຂ້າມ ${data.skipped.length} ລາຍການ — ເບິ່ງລາຍລະອຽດດ້ານລຸ່ມ</span>` : ''),
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'ໄປໜ້າລາຍການ',
                    showCancelButton: true,
                    cancelButtonText: 'ນຳເຂົ້າເພີ່ມ',
                    cancelButtonColor: '#6366f1',
                    reverseButtons: true,
                }).then(r => {
                    if (r.isConfirmed) {
                        window.location.href = '{{ route('employees.index') }}';
                    } else {
                        btnReset.click();
                    }
                });
            })
            .catch(err => {
                submitSpinner.classList.remove('show');
                btnSubmit.disabled = false;
                Swal.fire({ icon: 'error', title: 'ເກີດຂໍ້ຜິດພາດ', text: err?.message || 'ບໍ່ສາມາດຕິດຕໍ່ server ໄດ້', confirmButtonColor: '#6366f1' });
            });
        });
    });
})();
</script>
@endpush
