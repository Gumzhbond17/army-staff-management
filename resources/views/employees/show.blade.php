@extends('layouts.mainLayout')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* ===== Blue glassmorphism background ===== */
    body {
        background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 30%, #bfdbfe 60%, #e0f2fe 100%) !important;
        background-attachment: fixed !important;
    }
    body::before {
        content: '';
        position: fixed;
        top: -150px; left: -150px;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(14,165,233,0.20) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    body::after {
        content: '';
        position: fixed;
        bottom: -150px; right: -100px;
        width: 450px; height: 450px;
        background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }

    .page-wrapper { position: relative; z-index: 1; padding: 1.5rem 0 4rem; }

    /* ===== Page header ===== */
    .page-header {
        background: rgba(255,255,255,0.55);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.80);
        border-radius: 20px;
        padding: 1.25rem 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 24px rgba(14,165,233,0.10);
    }
    .page-header h2 { font-size: 1.4rem; font-weight: 700; color: #0c4a6e; margin: 0; }
    .page-header p  { color: #64748b; margin: 0.2rem 0 0; font-size: 0.85rem; }

    /* ===== Glass card ===== */
    .glass-card {
        background: rgba(255,255,255,0.62);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,0.85);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(14,165,233,0.10), 0 1px 0 rgba(255,255,255,0.9) inset;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    /* ===== Section header ===== */
    .section-header {
        background: linear-gradient(90deg, rgba(14,165,233,0.10) 0%, rgba(59,130,246,0.06) 100%);
        border-bottom: 1px solid rgba(14,165,233,0.14);
        padding: 0.85rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .section-icon {
        width: 30px; height: 30px;
        background: linear-gradient(135deg, #0ea5e9, #3b82f6);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .section-header h6 {
        font-weight: 700;
        color: #0c4a6e;
        margin: 0;
        font-size: 0.875rem;
        letter-spacing: 0.3px;
    }

    .card-body-inner { padding: 1.25rem 1.5rem; }

    /* ===== Photo area ===== */
    .employee-photo-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
        min-width: 120px;
    }
    .employee-photo {
        width: 110px; height: 138px;
        border-radius: 14px;
        object-fit: cover;
        border: 3px solid rgba(14,165,233,0.35);
        box-shadow: 0 4px 16px rgba(14,165,233,0.18);
    }
    .employee-photo-placeholder {
        width: 110px; height: 138px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(14,165,233,0.08), rgba(59,130,246,0.10));
        border: 2px dashed rgba(14,165,233,0.35);
        display: flex; align-items: center; justify-content: center;
        color: #93c5fd;
        font-size: 3rem;
    }
    .photo-label {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 600;
        margin-top: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .photo-divider {
        width: 1.5px;
        align-self: stretch;
        flex-shrink: 0;
        background: linear-gradient(to bottom, transparent 5%, rgba(14,165,233,0.22) 30%, rgba(14,165,233,0.22) 70%, transparent 95%);
        border-radius: 2px;
    }

    /* ===== Info field ===== */
    .info-item { margin-bottom: 0.15rem; }
    .info-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.2rem;
    }
    .info-value {
        font-size: 0.9rem;
        color: #0f2544;
        font-weight: 500;
        min-height: 1.4rem;
    }
    .info-value.empty { color: #94a3b8; font-weight: 400; }

    /* ===== Gender / blood badges ===== */
    .badge-male   { background: rgba(59,130,246,0.12); color: #1d4ed8; border: 1px solid rgba(59,130,246,0.2); border-radius: 50px; padding: 0.2rem 0.75rem; font-size: 0.82rem; font-weight: 600; }
    .badge-female { background: rgba(236,72,153,0.10); color: #9d174d; border: 1px solid rgba(236,72,153,0.2); border-radius: 50px; padding: 0.2rem 0.75rem; font-size: 0.82rem; font-weight: 600; }
    .badge-blood  { background: rgba(239,68,68,0.10); color: #b91c1c; border: 1px solid rgba(239,68,68,0.2); border-radius: 50px; padding: 0.2rem 0.6rem; font-size: 0.82rem; font-weight: 700; display: inline-block; min-width: 36px; text-align: center; }

    /* ===== Children table ===== */
    .children-table { border-radius: 12px; overflow: hidden; }
    .children-table thead th {
        background: rgba(14,165,233,0.08);
        color: #0c4a6e;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-color: rgba(14,165,233,0.15);
        padding: 0.65rem 0.85rem;
    }
    .children-table tbody td {
        font-size: 0.87rem;
        color: #1e3a5f;
        border-color: rgba(14,165,233,0.10);
        padding: 0.6rem 0.85rem;
        vertical-align: middle;
    }
    .children-table tbody tr:hover td { background: rgba(14,165,233,0.04); }

    /* ===== Section divider ===== */
    .section-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(14,165,233,0.18), transparent);
        margin: 1rem 0;
    }

    /* ===== Action buttons ===== */
    .btn-edit {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        border: none; border-radius: 10px; color: #fff;
        font-weight: 600; font-size: 0.85rem; padding: 0.55rem 1.25rem;
        box-shadow: 0 4px 12px rgba(245,158,11,0.35);
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-edit:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(245,158,11,0.45); color: #fff; }
    .btn-back {
        background: rgba(255,255,255,0.70);
        border: 1.5px solid rgba(148,163,184,0.4);
        border-radius: 10px; color: #64748b;
        font-weight: 500; font-size: 0.85rem; padding: 0.55rem 1.25rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-back:hover { background: rgba(255,255,255,0.9); border-color: #0ea5e9; color: #0ea5e9; }
    .btn-delete {
        background: rgba(239,68,68,0.08);
        border: 1.5px solid rgba(239,68,68,0.25);
        border-radius: 10px; color: #ef4444;
        font-weight: 500; font-size: 0.85rem; padding: 0.55rem 1.25rem;
        transition: all 0.2s ease;
    }
    .btn-delete:hover { background: rgba(239,68,68,0.15); color: #dc2626; }

    /* ===== Date chip ===== */
    .date-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: rgba(14,165,233,0.08);
        border: 1px solid rgba(14,165,233,0.18);
        border-radius: 8px;
        padding: 0.35rem 0.75rem;
        font-size: 0.85rem;
        color: #0c4a6e;
        font-weight: 500;
    }
    .date-chip i { color: #0ea5e9; font-size: 0.8rem; }
</style>
@endpush

@section('content')
<div class="page-wrapper">

    @if(session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success', title: 'ສຳເລັດ',
            text: '{{ session('success') }}',
            confirmButtonColor: '#0ea5e9', timer: 2500, showConfirmButton: false,
        });
    });
    </script>
    @endif

    {{-- Page Header --}}
    <div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h2><i class="bi bi-person-lines-fill me-2"></i>ຂໍ້ມູນພະນັກງານ</h2>
            <p>{{ $employee->full_name }} · {{ $employee->unit->name ?? '' }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn-edit btn">
                    <i class="bi bi-pencil me-1"></i> ແກ້ໄຂ
                </a>
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                      onsubmit="return confirm('ທ່ານຕ້ອງການລຶບຂໍ້ມູນນີ້ແທ້ບໍ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete btn">
                        <i class="bi bi-trash me-1"></i> ລຶບ
                    </button>
                </form>
            @endif
            <a href="{{ route('employees.index') }}" class="btn-back btn">
                <i class="bi bi-arrow-left me-1"></i> ກັບຄືນ
            </a>
        </div>
    </div>

    {{-- ================================================================
         ໝວດ I: ຂໍ້ມູນທົ່ວໄປ
    ================================================================ --}}
    <div class="glass-card">
        <div class="section-header">
            <div class="section-icon"><i class="bi bi-person-badge"></i></div>
            <h6>ໝວດ I · ຂໍ້ມູນທົ່ວໄປ</h6>
        </div>
        <div class="card-body-inner">
            <div class="d-flex align-items-start gap-4">

                {{-- Photo --}}
                <div class="employee-photo-wrap">
                    @if($employee->photoUrl)
                        <img src="{{ $employee->photoUrl }}" class="employee-photo" alt="ຮູບຖ່າຍ">
                    @else
                        <div class="employee-photo-placeholder">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                    <div class="photo-label">ຮູບຖ່າຍ</div>
                </div>

                {{-- Vertical divider --}}
                <div class="photo-divider"></div>

                {{-- Fields --}}
                <div class="grow">
                    <div class="row g-3">
                        <div class="col-md-3 info-item">
                            <div class="info-label">ເພດ</div>
                            <div class="info-value">
                                @if($employee->gender === 'male')
                                    <span class="badge-male"><i class="bi bi-gender-male me-1"></i>ຊາຍ</span>
                                @elseif($employee->gender === 'female')
                                    <span class="badge-female"><i class="bi bi-gender-female me-1"></i>ຍິງ</span>
                                @else
                                    <span class="empty">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 info-item">
                            <div class="info-label">ຊື່ ແລະ ນາມສະກຸນ</div>
                            <div class="info-value">{{ $employee->full_name }}</div>
                        </div>
                        <div class="col-md-3 info-item">
                            <div class="info-label">ກອງປະຈຳ</div>
                            <div class="info-value">{{ $employee->unit->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 info-item">
                            <div class="info-label">ວັນເດືອນປີເກີດ</div>
                            <div class="info-value">
                                @if($employee->dob)
                                    <span class="date-chip"><i class="bi bi-calendar3"></i>{{ $employee->dob->format('d/m/Y') }}</span>
                                @else
                                    <span class="empty">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 info-item">
                            <div class="info-label">ລະຫັດນາຍທະຫານ</div>
                            <div class="info-value">{{ $employee->officer_code ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 info-item">
                            <div class="info-label">ເລກບັດປະຈຳຕົວ</div>
                            <div class="info-value">{{ $employee->id_card_number ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 info-item">
                            <div class="info-label">ໜ້າທີ່ຮັບຜິດຊອບພັກ</div>
                            <div class="info-value">{{ $employee->party_duty ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 info-item">
                            <div class="info-label">ໜ້າທີ່ຮັບຜິດຊອບບັນຊາ</div>
                            <div class="info-value">{{ $employee->command_duty ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 info-item">
                            <div class="info-label">ສະຖານະການເຮັດວຽກ</div>
                            <div class="info-value">{{ $employee->workingStatus->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3 info-item">
                            <div class="info-label">ໝູ່ເລືອດ</div>
                            <div class="info-value">
                                @if($employee->blood_group)
                                    <span class="badge-blood">{{ $employee->blood_group }}</span>
                                @else
                                    <span class="empty">-</span>
                                @endif
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
    <div class="glass-card">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8)"><i class="bi bi-geo-alt"></i></div>
            <h6>ໝວດ II · ສະຖານທີ່ເກີດ</h6>
        </div>
        <div class="card-body-inner">
            <div class="row g-3">
                <div class="col-md-4 info-item">
                    <div class="info-label">ແຂວງ</div>
                    <div class="info-value">{{ $employee->birthProvince->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ເມືອງ</div>
                    <div class="info-value">{{ $employee->birthDistrict->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ບ້ານ</div>
                    <div class="info-value">{{ $employee->birth_village ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         ໝວດ III: ທີ່ຢູ່ປັດຈຸບັນ
    ================================================================ --}}
    <div class="glass-card">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#10b981,#34d399)"><i class="bi bi-house-check"></i></div>
            <h6>ໝວດ III · ທີ່ຢູ່ປັດຈຸບັນ</h6>
        </div>
        <div class="card-body-inner">
            <div class="row g-3">
                <div class="col-md-4 info-item">
                    <div class="info-label">ແຂວງ</div>
                    <div class="info-value">{{ $employee->currentProvince->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ເມືອງ</div>
                    <div class="info-value">{{ $employee->currentDistrict->name ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ບ້ານ</div>
                    <div class="info-value">{{ $employee->current_village ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         ໝວດ IV: ການສຶກສາ
    ================================================================ --}}
    <div class="glass-card">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)"><i class="bi bi-mortarboard"></i></div>
            <h6>ໝວດ IV · ການສຶກສາ</h6>
        </div>
        <div class="card-body-inner">
            <div class="row g-3">
                <div class="col-md-4 info-item">
                    <div class="info-label">ລະດັບວັດທະນະທຳ</div>
                    <div class="info-value">{{ $employee->culture_level ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ລະດັບທິດສະດີ</div>
                    <div class="info-value">{{ $employee->theory_level ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ຮຽນທິດສະດີຈາກ</div>
                    <div class="info-value">{{ $employee->theory_from ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ລະດັບວິຊາສະເພາະ</div>
                    <div class="info-value">{{ $employee->profession_level ?? '-' }}</div>
                </div>
                <div class="col-md-8 info-item">
                    <div class="info-label">ຮຽນວິຊາສະເພາະຈາກ</div>
                    <div class="info-value">{{ $employee->profession_from ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         ໝວດ V: ສັນຊາດ / ຊົນເຜົ່າ / ຊົນຊັ້ນ
    ================================================================ --}}
    <div class="glass-card">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#ec4899,#f472b6)"><i class="bi bi-globe-asia-australia"></i></div>
            <h6>ໝວດ V · ສັນຊາດ / ຊົນເຜົ່າ / ຊົນຊັ້ນ</h6>
        </div>
        <div class="card-body-inner">
            <div class="row g-3">
                <div class="col-md-4 info-item">
                    <div class="info-label">ສັນຊາດ</div>
                    <div class="info-value">{{ $employee->nationality ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ເຊື້ອຊາດ</div>
                    <div class="info-value">{{ $employee->ethnicity_group ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ຊົນເຜົ່າ</div>
                    <div class="info-value">{{ $employee->tribe ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ສາດສະໜາ</div>
                    <div class="info-value">{{ $employee->religion ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ຊົນຊັ້ນກ່ອນປີ 1975</div>
                    <div class="info-value">{{ $employee->class_before_1975 ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ຊົນຊັ້ນຫຼັງປີ 1975</div>
                    <div class="info-value">{{ $employee->class_after_1975 ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         ໝວດ VI: ວັນທີສຳຄັນ
    ================================================================ --}}
    <div class="glass-card">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#f97316,#fb923c)"><i class="bi bi-calendar-event"></i></div>
            <h6>ໝວດ VI · ວັນທີສຳຄັນ</h6>
        </div>
        <div class="card-body-inner">
            <div class="row g-3">
                @foreach([
                    'join_revolution_date' => ['label' => 'ວັນເຂົ້າປະຕິວັດ',   'icon' => 'bi-flag-fill'],
                    'join_army_date'       => ['label' => 'ວັນເຂົ້າທະຫານ',      'icon' => 'bi-shield-fill'],
                    'candidate_party_date' => ['label' => 'ວັນເຂົ້າພັກສຳຮອງ',   'icon' => 'bi-calendar-plus'],
                    'full_party_date'      => ['label' => 'ວັນເຂົ້າພັກສົມບູນ',  'icon' => 'bi-calendar-check'],
                    'current_rank_date'    => ['label' => 'ວັນໄດ້ຊັ້ນປັດຈຸບັນ', 'icon' => 'bi-award-fill'],
                ] as $field => $meta)
                <div class="col-md-4 info-item">
                    <div class="info-label">{{ $meta['label'] }}</div>
                    <div class="info-value">
                        @if($employee->$field)
                            <span class="date-chip">
                                <i class="bi {{ $meta['icon'] }}"></i>
                                {{ $employee->$field->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="empty">-</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ================================================================
         ໝວດ VII: ຄອບຄົວ ແລະ ວິໄນ
    ================================================================ --}}
    <div class="glass-card">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#14b8a6,#2dd4bf)"><i class="bi bi-people-fill"></i></div>
            <h6>ໝວດ VII · ຄອບຄົວ ແລະ ວິໄນ</h6>
        </div>
        <div class="card-body-inner">
            <div class="row g-3 mb-3">
                <div class="col-md-4 info-item">
                    <div class="info-label">ຊື່ພໍ່ແມ່</div>
                    <div class="info-value">{{ $employee->parents_name ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ຊື່ຄູ່ຊີວິດ</div>
                    <div class="info-value">{{ $employee->spouse_name ?? '-' }}</div>
                </div>
                <div class="col-md-4 info-item">
                    <div class="info-label">ຈຳນວນລູກ</div>
                    <div class="info-value">
                        {{ $employee->children->count() }} ຄົນ
                    </div>
                </div>
            </div>

            {{-- Children table --}}
            @if($employee->children->count() > 0)
            <div class="section-divider"></div>
            <div class="table-responsive">
                <table class="table children-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
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
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>{{ $child->first_name ?? '-' }}</td>
                            <td>{{ $child->last_name ?? '-' }}</td>
                            <td>
                                @if($child->dob)
                                    <span class="date-chip" style="font-size:0.8rem; padding:0.2rem 0.6rem;">
                                        <i class="bi bi-calendar3"></i>{{ $child->dob->format('d/m/Y') }}
                                    </span>
                                @else -
                                @endif
                            </td>
                            <td>
                                @if($child->gender === 'male')
                                    <span class="badge-male" style="font-size:0.78rem; padding:0.15rem 0.6rem;"><i class="bi bi-gender-male me-1"></i>ຊາຍ</span>
                                @elseif($child->gender === 'female')
                                    <span class="badge-female" style="font-size:0.78rem; padding:0.15rem 0.6rem;"><i class="bi bi-gender-female me-1"></i>ຍິງ</span>
                                @else -
                                @endif
                            </td>
                            <td class="text-muted">{{ $child->note ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if($employee->previous_units || $employee->discipline_record)
            <div class="section-divider"></div>
            <div class="row g-3">
                <div class="col-md-6 info-item">
                    <div class="info-label">ກອງເກົ່າທີ່ເຄີຍສັງກັດ</div>
                    <div class="info-value" style="white-space:pre-line">{{ $employee->previous_units ?? '-' }}</div>
                </div>
                <div class="col-md-6 info-item">
                    <div class="info-label">ປະຫວັດການວິໄນ</div>
                    <div class="info-value" style="white-space:pre-line">{{ $employee->discipline_record ?? '-' }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ================================================================
         ໝວດ VIII: ປະຫວັດການເຄື່ອນໄຫວ
    ================================================================ --}}
    <div class="glass-card">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa)"><i class="bi bi-journal-text"></i></div>
            <h6>ໝວດ VIII · ປະຫວັດການເຄື່ອນໄຫວ</h6>
        </div>
        <div class="card-body-inner">
            <div class="info-label mb-1">ປະຫວັດການເຄື່ອນໄຫວ <span class="text-muted" style="font-size:0.7rem; font-weight:400">(ແຕ່ອາຍຸ 8 ປີ)</span></div>
            <div class="info-value" style="white-space:pre-line; line-height:1.7">
                {{ $employee->biography ?? '-' }}
            </div>
        </div>
    </div>

</div>
@endsection
