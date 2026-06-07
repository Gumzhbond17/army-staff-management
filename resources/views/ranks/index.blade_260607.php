@extends('layouts.mainLayout')

@section('content')
    <div class="row mt-4">
        <h4 class="mb-4"><i class="bi bi-award me-2"></i>ຈັດການຂໍ້ມູນການເລື່ອນຊັ້ນ</h4>
        <div class="card">
            <div class="card-header">
                <form action="{{ URL('ranks') }}" method="GET"
                    class="d-flex align-items-center justify-content-between gap-2 flex-wrap py-2 px-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="input-group" style="width: 260px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted me-1"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="ຄົ້ນຫາ..."
                                name="search" value="{{ request('search') }}">
                        </div>

                        <button class="btn btn-outline-primary d-flex align-items-center gap-1" type="submit">
                            <i class="bi bi-search me-1"></i>
                            <span>ຄົ້ນຫາ</span>
                        </button>

                        <a href="{{ URL('ranks') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            <span>ໂຫຼດຂໍ້ມູນ</span>
                        </a>
                    </div>

                    <a href="{{ URL('ranks/create') }}" class="btn btn-success d-flex align-items-center gap-1">
                        <i class="bi bi-plus-lg me-1"></i>
                        <span>ເພີ່ມຂໍ້ມູນ</span>
                    </a>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted fw-medium"
                                    style="font-size: 14px; letter-spacing: .05em; width: 56px;">#</th>
                                <th class="text-muted fw-medium" style="font-size: 14px; letter-spacing: .05em;">ຊື່/ລາຍການ
                                </th>
                                <th class="text-muted fw-medium" style="font-size: 14px; letter-spacing: .05em;">ສະຖານະ</th>
                                <th class="text-muted fw-medium" style="font-size: 14px; letter-spacing: .05em;">ສ້າງວັນທີ
                                </th>
                                <th class="text-muted fw-medium" style="font-size: 14px; letter-spacing: .05em;">ແກ້ໄຂວັນທີ
                                </th>
                                <th class="text-muted fw-medium"
                                    style="font-size: 14px; letter-spacing: .05em; width: 130px;">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranks as $rank)
                                <tr>
                                    <td>
                                        <span
                                            class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-secondary"
                                            style="width: 26px; height: 26px; font-size: 12px; font-weight: 500;">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="fw-medium">
                                        {{ $rank->name }}
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill d-inline-flex align-items-center gap-1"
                                            style="background-color: {{ $rank->is_active ? '#EAF3DE' : '#FCEBEB' }};
                 color: {{ $rank->is_active ? '#27500A' : '#501313' }};
                 font-weight: 500; font-size: 12px;">
                                            <span class="rounded-circle"
                                                style="width: 6px; height: 6px;
                     background: {{ $rank->is_active ? '#3B6D11' : '#A32D2D' }};
                     display: inline-block;"></span>
                                            {{ $rank->is_active ? 'ໃຊ້ງານ' : 'ບໍ່ໃຊ້ງານ' }}
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size: 13px; font-family: monospace;">
                                        {{ $rank->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="text-muted" style="font-size: 13px; font-family: monospace;">
                                        {{ $rank->updated_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ URL('ranks/' . $rank->id . '/edit') }}"
                                                class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-pencil" style="font-size: 13px;"></i>
                                                ແກ້ໄຂ
                                            </a>
                                            <form action="{{ URL('ranks/' . $rank->id) }}" method="POST" class="d-inline">
                                                <form action="" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                                                        onclick="return confirm('ທ່ານຕ້ອງການລຶບລາຍການນີ້ບໍ?')">
                                                        <i class="bi bi-trash" style="font-size: 13px;"></i>
                                                        ລຶບ
                                                    </button>
                                                </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3 mx-3">{{ $ranks->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
