@extends('layouts.mainLayout')

@section('content')
<div class="row mt-4">
    <h4 class="mb-4"><i class="bi bi-person-check me-2"></i>ຄົ້ນຫາຂໍ້ມູນພະນັກງານ</h4>
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap py-2 px-3">

                @if(session('success'))
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'ສຳເລັດ',
                        text: '{{ session("success") }}',
                        confirmButtonColor: '#6366f1',
                        timer: 2500,
                        showConfirmButton: false,
                    });
                });
                </script>
                @endif

                @if($errors->any())
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'ມີຂໍ້ຜິດພາດ',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        confirmButtonColor: '#6366f1',
                    });
                });
                </script>
                @endif

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

                <button type="button" class="btn btn-success d-flex align-items-center gap-1"
                    onclick="location.href='{{ route('employees.create') }}'">
                    <i class="bi bi-plus-lg me-1"></i> ເພີ່ມຂໍ້ມູນ
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive rounded-3 border">
                <table class="table table-hover align-middle mb-0">
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
                            <th style="width:140px;">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>
                                    <span class="d-inline-flex align-items-center justify-content-center
                                        rounded-circle bg-light text-secondary"
                                        style="width:26px;height:26px;font-size:12px;font-weight:500;">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                {{-- PHOTO — use model accessor $employee->photoUrl --}}
                                <td>
                                    @if($employee->photo)
                                        <img src="{{ $employee->photoUrl }}"
                                             alt="photo"
                                             class="rounded-circle object-fit-cover"
                                             style="width:36px;height:36px;border:2px solid #e2e8f0;">
                                    @else
                                        <span class="d-inline-flex align-items-center justify-content-center
                                            rounded-circle bg-secondary text-white"
                                            style="width:36px;height:36px;font-size:14px;">
                                            <i class="bi bi-person"></i>
                                        </span>
                                    @endif
                                </td>

                                <td class="fw-medium">{{ $employee->full_name }}</td>

                                <td>
                                    @if($employee->officer_code)
                                        <span class="badge bg-light text-dark border"
                                              style="font-family:monospace;font-size:12px;">
                                            {{ $employee->officer_code }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- BUG FIX: Compare 'male'/'female' (English DB values),
                                     NOT 'ຊາຍ'/'ຍິງ' which never matched --}}
                                <td>
                                    @if($employee->gender === 'male')
                                        <span class="text-primary"><i class="bi bi-gender-male"></i> ຊາຍ</span>
                                    @elseif($employee->gender === 'female')
                                        <span class="text-danger"><i class="bi bi-gender-female"></i> ຍິງ</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>{{ $employee->unit?->name ?? '—' }}</td>

                                <td>
                                    @if($employee->workingStatus)
                                        <span class="badge rounded-pill"
                                              style="background:#EAF3DE;color:#27500A;font-weight:500;font-size:12px;">
                                            <span class="rounded-circle d-inline-block me-1"
                                                  style="width:6px;height:6px;background:#3B6D11;"></span>
                                            {{ $employee->workingStatus->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($employee->blood_group)
                                        <span class="badge rounded-pill"
                                              style="background:#FCEBEB;color:#501313;font-weight:600;font-size:12px;">
                                            {{ $employee->blood_group }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-muted" style="font-size:13px;">
                                    {{ $employee->dob ? $employee->dob->format('d/m/Y') : '—' }}
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('employees.show', $employee->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye" style="font-size:13px;"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-pencil" style="font-size:13px;"></i>
                                        </a>

                                        {{-- BUG FIX: Hidden DELETE form — JS confirms then submits --}}
                                        <form id="del-{{ $employee->id }}"
                                              action="{{ route('employees.destroy', $employee->id) }}"
                                              method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger btn-delete"
                                                data-name="{{ $employee->full_name }}"
                                                data-form="del-{{ $employee->id }}">
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
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const name   = this.dataset.name;
        const formId = this.dataset.form;
        Swal.fire({
            icon: 'warning',
            title: 'ຢືນຢັນການລຶບ',
            html: 'ທ່ານຕ້ອງການລຶບ <strong>' + name + '</strong> ຫຼືບໍ່?',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ລຶບເລີຍ',
            cancelButtonText: 'ຍົກເລີກ',
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    });
});
</script>
@endpush
