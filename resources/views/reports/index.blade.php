@extends('layouts.mainLayout')

@push('styles')
<style>
    .stat-card {
        border-radius: 16px;
        border: none;
        padding: 1.5rem 1.4rem 1.2rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.13) !important;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.1;
    }
    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        opacity: 0.75;
    }
    .stat-percent {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
    }
    .stat-progress {
        height: 5px;
        border-radius: 3px;
        margin-top: 1rem;
    }
    .stat-progress .bar {
        height: 100%;
        border-radius: 3px;
        transition: width 0.8s ease;
    }

    /* Gender */
    .card-male   { background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); border-left: 4px solid #3b82f6 !important; }
    .card-female { background: linear-gradient(135deg, #fce7f3 0%, #fdf2f8 100%); border-left: 4px solid #ec4899 !important; }

    /* Blood groups */
    .card-blood-a  { background: linear-gradient(135deg, #fee2e2 0%, #fff5f5 100%); border-left: 4px solid #ef4444 !important; }
    .card-blood-b  { background: linear-gradient(135deg, #ffedd5 0%, #fff7ed 100%); border-left: 4px solid #f97316 !important; }
    .card-blood-o  { background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%); border-left: 4px solid #22c55e !important; }
    .card-blood-ab { background: linear-gradient(135deg, #f3e8ff 0%, #faf5ff 100%); border-left: 4px solid #a855f7 !important; }

    /* Section headers */
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 1.1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e9ecef;
    }
    .section-title h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin: 0;
    }
    .section-icon-wrap {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    /* Charts */
    .chart-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.4rem 1.2rem 1rem;
        box-shadow: 0 2px 14px rgba(0,0,0,0.07);
        height: 100%;
    }
    .chart-card h6 {
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.2px;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    /* Summary total badge */
    .total-badge {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        border-radius: 12px;
        padding: 0.55rem 1.1rem;
        font-size: 0.95rem;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }
</style>
@endpush

@section('content')
<div class="mt-4 mb-5">

    {{-- ── Page header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0 fw-bold" style="color:#1e1b4b;">
            <i class="bi bi-file-earmark-bar-graph me-2" style="color:#6366f1;"></i>ລາຍງານຂໍ້ມູນພະນັກງານ
        </h4>
        <div class="total-badge">
            <i class="bi bi-people-fill me-1"></i>ທັງໝົດ {{ number_format($totalEmployees) }} ຄົນ
        </div>
    </div>

    {{-- ════════════════════════════════════════
         SECTION 1 — GENDER
    ════════════════════════════════════════ --}}
    <div class="section-title">
        <div class="section-icon-wrap" style="background:rgba(99,102,241,0.12);color:#6366f1;">
            <i class="bi bi-gender-ambiguous"></i>
        </div>
        <h5>ສະຖິຕິຈໍາແນກຕາມເພດ</h5>
    </div>

    <div class="row g-3 mb-4">
        {{-- Male --}}
        <div class="col-md-6">
            <div class="card stat-card card-male shadow-sm">
                <div class="stat-percent" style="background:rgba(59,130,246,0.12);color:#3b82f6;">
                    {{ $totalEmployees > 0 ? number_format(($maleCount / $totalEmployees) * 100, 1) : '0.0' }}%
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(59,130,246,0.14);">
                        <i class="bi bi-gender-male" style="color:#3b82f6;"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color:#3b82f6;">{{ number_format($maleCount) }}</div>
                        <div class="stat-label" style="color:#3b82f6;">ພະນັກງານຊາຍ</div>
                    </div>
                </div>
                <div class="stat-progress" style="background:rgba(59,130,246,0.15);">
                    <div class="bar" style="width:{{ $totalEmployees > 0 ? ($maleCount / $totalEmployees) * 100 : 0 }}%; background:#3b82f6;"></div>
                </div>
            </div>
        </div>

        {{-- Female --}}
        <div class="col-md-6">
            <div class="card stat-card card-female shadow-sm">
                <div class="stat-percent" style="background:rgba(236,72,153,0.12);color:#ec4899;">
                    {{ $totalEmployees > 0 ? number_format(($femaleCount / $totalEmployees) * 100, 1) : '0.0' }}%
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(236,72,153,0.14);">
                        <i class="bi bi-gender-female" style="color:#ec4899;"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color:#ec4899;">{{ number_format($femaleCount) }}</div>
                        <div class="stat-label" style="color:#ec4899;">ພະນັກງານຍິງ</div>
                    </div>
                </div>
                <div class="stat-progress" style="background:rgba(236,72,153,0.15);">
                    <div class="bar" style="width:{{ $totalEmployees > 0 ? ($femaleCount / $totalEmployees) * 100 : 0 }}%; background:#ec4899;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         SECTION 2 — WORKING STATUS
    ════════════════════════════════════════ --}}
    @php
        $statusPalette = [
            ['accent' => '#6366f1', 'bg' => 'rgba(99,102,241,0.08)',  'icon_bg' => 'rgba(99,102,241,0.14)',  'border' => '#6366f1', 'grad_from' => '#ede9fe'],
            ['accent' => '#10b981', 'bg' => 'rgba(16,185,129,0.08)',  'icon_bg' => 'rgba(16,185,129,0.14)',  'border' => '#10b981', 'grad_from' => '#d1fae5'],
            ['accent' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.08)',  'icon_bg' => 'rgba(245,158,11,0.14)',  'border' => '#f59e0b', 'grad_from' => '#fef3c7'],
            ['accent' => '#ef4444', 'bg' => 'rgba(239,68,68,0.08)',   'icon_bg' => 'rgba(239,68,68,0.14)',   'border' => '#ef4444', 'grad_from' => '#fee2e2'],
            ['accent' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.08)',  'icon_bg' => 'rgba(139,92,246,0.14)',  'border' => '#8b5cf6', 'grad_from' => '#ede9fe'],
            ['accent' => '#06b6d4', 'bg' => 'rgba(6,182,212,0.08)',   'icon_bg' => 'rgba(6,182,212,0.14)',   'border' => '#06b6d4', 'grad_from' => '#cffafe'],
        ];
        $statusIcons = [
            'bi-briefcase-fill',
            'bi-person-check-fill',
            'bi-person-dash-fill',
            'bi-clock-history',
            'bi-archive-fill',
            'bi-person-x-fill',
        ];
    @endphp

    <div class="section-title">
        <div class="section-icon-wrap" style="background:rgba(16,185,129,0.12);color:#10b981;">
            <i class="bi bi-briefcase-fill"></i>
        </div>
        <h5>ສະຖິຕິຕາມສະຖານະການເຮັດວຽກ</h5>
    </div>

    <div class="row g-3 mb-4">
        @foreach($allStatuses as $i => $status)
        @php
            $p    = $statusPalette[$i % count($statusPalette)];
            $cnt  = $statusCounts[$status->id] ?? 0;
            $ico  = $statusIcons[$i % count($statusIcons)];
            $pct  = $totalEmployees > 0 ? ($cnt / $totalEmployees) * 100 : 0;
        @endphp
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card shadow-sm"
                 style="background: linear-gradient(135deg, {{ $p['grad_from'] }} 0%, #fff 100%);
                        border-left: 4px solid {{ $p['border'] }} !important;">
                <div class="stat-percent" style="background:{{ $p['icon_bg'] }};color:{{ $p['accent'] }};">
                    {{ number_format($pct, 1) }}%
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:{{ $p['icon_bg'] }};">
                        <i class="bi {{ $ico }}" style="color:{{ $p['accent'] }};"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color:{{ $p['accent'] }};">{{ number_format($cnt) }}</div>
                        <div class="stat-label" style="color:{{ $p['accent'] }};">{{ $status->name }}</div>
                    </div>
                </div>
                <div class="stat-progress" style="background:{{ $p['icon_bg'] }};">
                    <div class="bar" style="width:{{ $pct }}%; background:{{ $p['accent'] }};"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ════════════════════════════════════════
         SECTION 3 — BLOOD GROUPS
    ════════════════════════════════════════ --}}
    @php
        $bloodConfig = [
            'A'  => ['class' => 'card-blood-a',  'accent' => '#ef4444', 'icon_bg' => 'rgba(239,68,68,0.14)'],
            'B'  => ['class' => 'card-blood-b',  'accent' => '#f97316', 'icon_bg' => 'rgba(249,115,22,0.14)'],
            'O'  => ['class' => 'card-blood-o',  'accent' => '#22c55e', 'icon_bg' => 'rgba(34,197,94,0.14)'],
            'AB' => ['class' => 'card-blood-ab', 'accent' => '#a855f7', 'icon_bg' => 'rgba(168,85,247,0.14)'],
        ];
        $totalBlood = array_sum($bloodGroups ? array_map(fn($g) => $bloodCounts[$g] ?? 0, $bloodGroups) : []);
    @endphp

    <div class="section-title">
        <div class="section-icon-wrap" style="background:rgba(239,68,68,0.1);color:#ef4444;">
            <i class="bi bi-droplet-fill"></i>
        </div>
        <h5>ສະຖິຕິຕາມໝູ່ເລືອດ</h5>
    </div>

    <div class="row g-3 mb-4">
        @foreach($bloodGroups as $group)
        @php
            $cfg = $bloodConfig[$group];
            $cnt = $bloodCounts[$group] ?? 0;
            $pct = $totalBlood > 0 ? ($cnt / $totalBlood) * 100 : 0;
        @endphp
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card {{ $cfg['class'] }} shadow-sm">
                <div class="stat-percent" style="background:{{ $cfg['icon_bg'] }};color:{{ $cfg['accent'] }};">
                    {{ number_format($pct, 1) }}%
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:{{ $cfg['icon_bg'] }};">
                        <i class="bi bi-droplet-fill" style="color:{{ $cfg['accent'] }}; font-size:1.3rem;"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color:{{ $cfg['accent'] }};">{{ number_format($cnt) }}</div>
                        <div class="stat-label" style="color:{{ $cfg['accent'] }};">ໝູ່ເລືອດ {{ $group }}</div>
                    </div>
                </div>
                <div class="stat-progress" style="background:{{ $cfg['icon_bg'] }};">
                    <div class="bar" style="width:{{ $pct }}%; background:{{ $cfg['accent'] }};"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ════════════════════════════════════════
         SECTION 4 — CHARTS
    ════════════════════════════════════════ --}}
    <div class="section-title">
        <div class="section-icon-wrap" style="background:rgba(6,182,212,0.1);color:#06b6d4;">
            <i class="bi bi-pie-chart-fill"></i>
        </div>
        <h5>ກຣາຟສະແດງຂໍ້ມູນ</h5>
    </div>

    <div class="row g-3 mb-5">
        {{-- Gender pie --}}
        <div class="col-md-4">
            <div class="chart-card">
                <h6 class="text-center">
                    <i class="bi bi-gender-ambiguous me-1" style="color:#6366f1;"></i>ສັດສ່ວນເພດ
                </h6>
                <div style="position:relative; height:230px;">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Blood group pie --}}
        <div class="col-md-4">
            <div class="chart-card">
                <h6 class="text-center">
                    <i class="bi bi-droplet-fill me-1 text-danger"></i>ສັດສ່ວນໝູ່ເລືອດ
                </h6>
                <div style="position:relative; height:230px;">
                    <canvas id="bloodChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Working status pie --}}
        <div class="col-md-4">
            <div class="chart-card">
                <h6 class="text-center">
                    <i class="bi bi-briefcase-fill me-1 text-success"></i>ສັດສ່ວນສະຖານະ
                </h6>
                <div style="position:relative; height:230px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    Chart.defaults.font.family = '"Google Sans", sans-serif';
    Chart.defaults.font.size   = 12;

    const sharedDoughnutOpts = {
        cutout: '64%',
        responsive: true,
        maintainAspectRatio: false,
        animation: { animateRotate: true, duration: 900 },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 14,
                    boxWidth: 11,
                    boxHeight: 11,
                    font: { size: 12 }
                }
            },
            tooltip: {
                callbacks: {
                    label: ctx => `  ${ctx.label}: ${ctx.formattedValue} ຄົນ`
                }
            }
        }
    };

    // ── Gender chart ──
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: ['ຊາຍ', 'ຍິງ'],
            datasets: [{
                data: [{{ $maleCount }}, {{ $femaleCount }}],
                backgroundColor: ['#3b82f6', '#ec4899'],
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: sharedDoughnutOpts
    });

    // ── Blood group chart ──
    new Chart(document.getElementById('bloodChart'), {
        type: 'doughnut',
        data: {
            labels: ['ໝູ່ A', 'ໝູ່ B', 'ໝູ່ O', 'ໝູ່ AB'],
            datasets: [{
                data: [
                    {{ $bloodCounts['A']  ?? 0 }},
                    {{ $bloodCounts['B']  ?? 0 }},
                    {{ $bloodCounts['O']  ?? 0 }},
                    {{ $bloodCounts['AB'] ?? 0 }}
                ],
                backgroundColor: ['#ef4444', '#f97316', '#22c55e', '#a855f7'],
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: sharedDoughnutOpts
    });

    // ── Working status chart ──
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($allStatuses->pluck('name')->values()) !!},
            datasets: [{
                data: {!! json_encode($allStatuses->map(fn($s) => $statusCounts[$s->id] ?? 0)->values()) !!},
                backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: sharedDoughnutOpts
    });
})();
</script>
@endpush
