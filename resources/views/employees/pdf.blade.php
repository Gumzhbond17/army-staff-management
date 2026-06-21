<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: phetsarath, sans-serif;
    font-size: 10.5pt;
    color: #111;
    line-height: 1.3;
    padding-bottom: 32mm; /* reserve space for fixed bottom block */
}

/* ── Header ── */
.hdr {
    text-align: center;
    border-bottom: 1.5px solid #1a3a6b;
    padding-bottom: 4px;
    margin-bottom: 4px;
}
.hdr .title { font-size: 15pt; font-weight: bold; color: #1a3a6b; }
.hdr .sub   { font-size: 10pt;  color: #555; margin-top: 1px; }

/* ── Section table (used for every section) ── */
.ft { width:100%; border-collapse:collapse; margin-bottom:2px; }
.ft td {
    border: 0.5px solid #94a3b8;
    padding: 2px 5px;
    vertical-align: middle;
    font-size: 10.5pt;
}

/* Section header row */
.sh {
    background: #1a3a6b;
    color: #fff;
    font-weight: bold;
    font-size: 10.5pt;
    padding: 3px 5px;
}

/* Label cell */
.lb {
    background: #eef2f9;
    color: #444;
    font-size: 9pt;
    font-weight: bold;
    white-space: nowrap;
    width: 82px;
}

/* Children sub-table */
.ct { width:100%; border-collapse:collapse; }
.ct th {
    background: #dce8f7; color: #1a3a6b;
    font-size: 9.5pt; font-weight: bold;
    padding: 2px 5px; border: 0.5px solid #94a3b8; text-align: center;
}
.ct td { padding:2px 5px; border:0.5px solid #94a3b8; text-align:center; font-size:10pt; }
.ct tr:nth-child(even) td { background:#f5f9ff; }

/* ── Fixed bottom block (signatures + footer) ── */
.bottom-block {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    padding-top: 4px;
}

/* Signature */
.sig   { width:100%; border-collapse:collapse; }
.sig td { text-align:center; font-size:10.5pt; padding:6px 6px 0; vertical-align:top; height:246px; }
.sig-line { border-top:0.8px solid #333; margin:4px 14px 3px; }
.sig-lbl  { font-size:11pt; color:#555; }

/* Footer */
.ftr {
    border-top: 1px solid #1a3a6b;
    margin-top: 6px; padding-top: 3px;
    font-size: 9pt; color: #666; text-align: center;
}

/* Badges */
.bm { color:#1d4ed8; font-weight:bold; }
.bf { color:#9d174d; font-weight:bold; }
.bb { color:#b91c1c; font-weight:bold; }
</style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<div class="hdr">
    <div class="title">ເອກະສານຂໍ້ມູນພະນັກງານ</div>
    {{-- <div class="sub">ກອງທັບປະຊາຊົນລາວ &nbsp;·&nbsp; ລະບົບຈັດການຂໍ້ມູນພະນັກງານ</div> --}}
</div>

{{-- ══ ໝວດ I · ຂໍ້ມູນທົ່ວໄປ  (fields 80% | photo 20%) ══ --}}
<table style="width:100%; border-collapse:collapse; margin-bottom:2px; border:0.5px solid #94a3b8;">
    <tr><td class="sh" colspan="2">ໝວດ I &nbsp;·&nbsp; ຂໍ້ມູນທົ່ວໄປ</td></tr>
    <tr>
        {{-- Fields column 80% --}}
        <td style="width:80%; vertical-align:top; padding:0; border-right:0.5px solid #94a3b8;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ຊື່ ແລະ ນາມສະກຸນ</td>
                    <td colspan="3" style="border:0.5px solid #94a3b8; padding:2px 5px; font-weight:bold; color:#1a3a6b; font-size:13pt;">{{ $employee->full_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ເພດ</td>
                    <td style="border:0.5px solid #94a3b8; padding:2px 4px;">
                        @if($employee->gender === 'male') <span class="bm">♂ ຊາຍ</span>
                        @elseif($employee->gender === 'female') <span class="bf">♀ ຍິງ</span>
                        @else - @endif
                    </td>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ວັນເດືອນປີເກີດ</td>
                    <td style="border:0.5px solid #94a3b8; padding:2px 4px;">{{ $employee->dob ? $employee->dob->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ໝູ່ເລືອດ</td>
                    <td style="border:0.5px solid #94a3b8; padding:2px 4px;"><span class="bb">{{ $employee->blood_group ?? '-' }}</span></td>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ສະຖານະ</td>
                    <td style="border:0.5px solid #94a3b8; padding:2px 4px;">{{ $employee->workingStatus->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ລະຫັດນາຍທະຫານ</td>
                    <td style="border:0.5px solid #94a3b8; padding:2px 4px;">{{ $employee->officer_code ?? '-' }}</td>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ເລກບັດປະຈຳຕົວ</td>
                    <td style="border:0.5px solid #94a3b8; padding:2px 4px;">{{ $employee->id_card_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ກອງປະຈຳ</td>
                    <td style="border:0.5px solid #94a3b8; padding:2px 4px;">{{ $employee->unit->name ?? '-' }}</td>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ໜ້າທີ່ຮັບຜິດຊອບພັກ</td>
                    <td style="border:0.5px solid #94a3b8; padding:2px 4px;">{{ $employee->party_duty ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="lb" style="border:0.5px solid #94a3b8; padding:2px 4px;">ໜ້າທີ່ຮັບຜິດຊອບບັນຊາ</td>
                    <td colspan="3" style="border:0.5px solid #94a3b8; padding:2px 4px;">{{ $employee->command_duty ?? '-' }}</td>
                </tr>
            </table>
        </td>
        {{-- Photo column 20% ≈ 37mm --}}
        <td style="width:20%; text-align:center; vertical-align:middle; padding:4px 6px;">
            @if($photoPath && file_exists($photoPath))
                <img src="{{ $photoPath }}" width="82" height="103"
                     style="width:82px; height:103px; border:1.5px solid #1a3a6b; display:block; margin:0 auto;">
            @else
                <div style="width:82px; height:103px; border:1.5px dashed #aaa; background:#f0f4fa; display:block; margin:0 auto;"></div>
            @endif
            <div style="font-size:8.5pt; color:#888; margin-top:3px;">ຮູບຖ່າຍ</div>
        </td>
    </tr>
</table>

{{-- ══ ໝວດ II · ສະຖານທີ່ເກີດ ══ --}}
<table class="ft" style="table-layout:fixed;">
    <colgroup>
        <col style="width:82px;"><col style="width:22%;"><col style="width:82px;"><col style="width:22%;"><col style="width:82px;"><col>
    </colgroup>
    <tr><td class="sh" colspan="6">ໝວດ II &nbsp;·&nbsp; ສະຖານທີ່ເກີດ</td></tr>
    <tr>
        <td class="lb">ແຂວງ</td><td>{{ $employee->birthProvince->name ?? '-' }}</td>
        <td class="lb">ເມືອງ</td><td>{{ $employee->birthDistrict->name ?? '-' }}</td>
        <td class="lb">ບ້ານ</td><td>{{ $employee->birth_village ?? '-' }}</td>
    </tr>
</table>

{{-- ══ ໝວດ III · ທີ່ຢູ່ປັດຈຸບັນ ══ --}}
<table class="ft" style="table-layout:fixed;">
    <colgroup>
        <col style="width:82px;"><col style="width:22%;"><col style="width:82px;"><col style="width:22%;"><col style="width:82px;"><col>
    </colgroup>
    <tr><td class="sh" colspan="6">ໝວດ III &nbsp;·&nbsp; ທີ່ຢູ່ປັດຈຸບັນ</td></tr>
    <tr>
        <td class="lb">ແຂວງ</td><td>{{ $employee->currentProvince->name ?? '-' }}</td>
        <td class="lb">ເມືອງ</td><td>{{ $employee->currentDistrict->name ?? '-' }}</td>
        <td class="lb">ບ້ານ</td><td>{{ $employee->current_village ?? '-' }}</td>
    </tr>
</table>

{{-- ══ ໝວດ IV · ການສຶກສາ ══ --}}
<table class="ft">
    <tr><td class="sh" colspan="6">ໝວດ IV &nbsp;·&nbsp; ການສຶກສາ</td></tr>
    <tr>
        <td class="lb">ລະດັບວັດທະນະທຳ</td><td>{{ $employee->culture_level ?? '-' }}</td>
        <td class="lb">ລະດັບທິດສະດີ</td><td>{{ $employee->theory_level ?? '-' }}</td>
        <td class="lb">ຮຽນທິດສະດີຈາກ</td><td>{{ $employee->theory_from ?? '-' }}</td>
    </tr>
    <tr>
        <td class="lb">ລະດັບວິຊາສະເພາະ</td><td>{{ $employee->profession_level ?? '-' }}</td>
        <td class="lb" colspan="2">ຮຽນວິຊາສະເພາະຈາກ</td>
        <td colspan="2">{{ $employee->profession_from ?? '-' }}</td>
    </tr>
</table>

{{-- ══ ໝວດ V · ສັນຊາດ / ຊົນເຜົ່າ / ຊົນຊັ້ນ ══ --}}
<table class="ft">
    <tr><td class="sh" colspan="6">ໝວດ V &nbsp;·&nbsp; ສັນຊາດ / ຊົນເຜົ່າ / ຊົນຊັ້ນ</td></tr>
    <tr>
        <td class="lb">ສັນຊາດ</td><td>{{ $employee->nationality ?? '-' }}</td>
        <td class="lb">ເຊື້ອຊາດ</td><td>{{ $employee->ethnicity_group ?? '-' }}</td>
        <td class="lb">ຊົນເຜົ່າ</td><td>{{ $employee->tribe ?? '-' }}</td>
    </tr>
    <tr>
        <td class="lb">ສາດສະໜາ</td><td>{{ $employee->religion ?? '-' }}</td>
        <td class="lb">ຊົນຊັ້ນກ່ອນ 1975</td><td>{{ $employee->class_before_1975 ?? '-' }}</td>
        <td class="lb">ຊົນຊັ້ນຫຼັງ 1975</td><td>{{ $employee->class_after_1975 ?? '-' }}</td>
    </tr>
</table>

{{-- ══ ໝວດ VI · ວັນທີສຳຄັນ ══ --}}
<table class="ft">
    <tr><td class="sh" colspan="6">ໝວດ VI &nbsp;·&nbsp; ວັນທີສຳຄັນ</td></tr>
    <tr>
        <td class="lb">ວັນເຂົ້າການປະຕິວັດ</td>
        <td>{{ $employee->join_revolution_date ? $employee->join_revolution_date->format('d/m/Y') : '-' }}</td>
        <td class="lb">ວັນເຂົ້າການທະຫານ</td>
        <td>{{ $employee->join_army_date ? $employee->join_army_date->format('d/m/Y') : '-' }}</td>
        <td class="lb">ວັນທີ່ໄດ້ຊັ້ນປັດຈຸບັນ</td>
        <td>{{ $employee->current_rank_date ? $employee->current_rank_date->format('d/m/Y') : '-' }}</td>
    </tr>
    <tr>
        <td class="lb">ວັນເຂົ້າພັກສຳຮອງ</td>
        <td>{{ $employee->candidate_party_date ? $employee->candidate_party_date->format('d/m/Y') : '-' }}</td>
        <td class="lb">ວັນເຂົ້າພັກສົມບູນ</td>
        <td>{{ $employee->full_party_date ? $employee->full_party_date->format('d/m/Y') : '-' }}</td>
        <td colspan="2"></td>
    </tr>
</table>

{{-- ══ ໝວດ VII · ຄອບຄົວ ແລະ ວິໄນ ══ --}}
<table class="ft">
    <tr><td class="sh" colspan="6">ໝວດ VII &nbsp;·&nbsp; ຄອບຄົວ ແລະ ວິໄນ</td></tr>
    <tr>
        <td class="lb">ຊື່ພໍ່ແມ່</td><td>{{ $employee->parents_name ?? '-' }}</td>
        <td class="lb">ຊື່ຄູ່ຊີວິດ</td><td>{{ $employee->spouse_name ?? '-' }}</td>
        <td class="lb">ຈຳນວນລູກ</td><td>{{ $employee->children->count() }} ຄົນ</td>
    </tr>
    @if($employee->children->count() > 0)
    <tr>
        <td colspan="6" style="padding:0;">
            <table class="ct">
                <thead>
                    <tr>
                        <th style="width:22px">#</th>
                        <th>ຊື່</th><th>ນາມສະກຸນ</th>
                        <th>ວັນເດືອນປີເກີດ</th><th>ເພດ</th><th>ໝາຍເຫດ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employee->children as $i => $child)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $child->first_name ?? '-' }}</td>
                        <td>{{ $child->last_name ?? '-' }}</td>
                        <td>{{ $child->dob ? $child->dob->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($child->gender === 'male') <span class="bm">ຊາຍ</span>
                            @elseif($child->gender === 'female') <span class="bf">ຍິງ</span>
                            @else - @endif
                        </td>
                        <td>{{ $child->note ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>
    @endif
    @if($employee->previous_units || $employee->discipline_record)
    <tr>
        <td class="lb">ກອງເກົ່າທີ່ເຄີຍສັງກັດ</td>
        <td colspan="2" style="white-space:pre-wrap; font-size:10.5pt;">{{ $employee->previous_units ?? '-' }}</td>
        <td class="lb">ການປະຕິບັດວິໄນ</td>
        <td colspan="2" style="white-space:pre-wrap; font-size:10.5pt;">{{ $employee->discipline_record ?? '-' }}</td>
    </tr>
    @endif
</table>

{{-- ══ ໝວດ VIII · ປະຫວັດການເຄື່ອນໄຫວ ══ --}}
@php
    $bio = $employee->biography ?? '-';
    if (mb_strlen($bio) > 600) {
        $bio = mb_substr($bio, 0, 600) . ' ...';
    }
@endphp
<table class="ft">
    <tr>
        <td class="sh">ໝວດ VIII &nbsp;·&nbsp; ປະຫວັດການເຄື່ອນໄຫວ <span style="font-size:6.5pt; font-weight:normal;">(ແຕ່ອາຍຸ 8 ປີ)</span></td>
    </tr>
    <tr>
        <td style="white-space:pre-wrap; font-size:10.5pt; line-height:1.5; padding:4px 5px; vertical-align:top;">{{ $bio }}</td>
    </tr>
</table>

{{-- ══ ລາຍເຊັນ + Footer  (fixed to bottom of page) ══ --}}
<div class="bottom-block">
    <table class="sig">
        <tr>
            <td style="text-align:left;">
                <div class="sig-lbl">ຜູ້ໃຫ້ຂໍ້ມູນ</div>
                <div class="sig-line"></div>
                <div class="sig-lbl">ລາຍເຊັນ / ຊື່</div>
            </td>
            <td style="text-align:center;">
                <div class="sig-lbl">ເຈົ້າໜ້າທີ່ຮັບຜິດຊອບ</div>
                <div class="sig-line"></div>
                <div class="sig-lbl">ລາຍເຊັນ / ຊື່</div>
            </td>
            <td style="text-align:right;">
                <div class="sig-lbl">ຜູ້ອຳນວຍການ / ຫົວໜ້າ</div>
                <div class="sig-line"></div>
                <div class="sig-lbl">ລາຍເຊັນ / ຊື່ / ຕາປະທັບ</div>
            </td>
        </tr>
    </table>
    <div class="ftr">
        ວັນທີພິມ: {{ now()->format('d/m/Y H:i') }}
        &nbsp;·&nbsp; ເອກະສານນີ້ອອກໂດຍ ກົມຄຸ້ມຄອງພະນັກງານ ລະບົບຈັດການຂໍ້ມູນພະນັກງານ
        &nbsp;·&nbsp; ຫ້າມນຳໃຊ້ໂດຍບໍ່ໄດ້ຮັບອະນຸຍາດ
    </div>
</div>

</body>
</html>
