@extends('layouts.mainLayout')

@section('content')
<div class="row mt-4 justify-content-center">
    <div class="col-lg-8">
        <h4 class="mb-4"><i class="bi bi-sliders me-2"></i>ຕັ້ງຄ່າໜ້າຫຼັກ</h4>

        @if(session('success'))
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'ສຳເລັດ',
                text: '{{ session('success') }}',
                confirmButtonColor: '#6366f1',
                timer: 2500,
                showConfirmButton: false,
            });
        });
        </script>
        @endif

        <div class="card">
            <div class="card-header py-3 px-4">
                <i class="bi bi-house-door me-1"></i> ຂໍ້ຄວາມທີ່ສະແດງໃນໜ້າຫຼັກ
            </div>
            <div class="card-body p-4">
                <form action="{{ route('site-settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">ຊື່ກະຊວງ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-bank"></i></span>
                            <input type="text" class="form-control @error('ministry_name') is-invalid @enderror"
                                   name="ministry_name"
                                   value="{{ old('ministry_name', $settings['ministry_name']) }}"
                                   placeholder="ກະຊວງປ້ອງກັນປະເທດ" required>
                            @error('ministry_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">ຊື່ກົມ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                            <input type="text" class="form-control @error('department_name') is-invalid @enderror"
                                   name="department_name"
                                   value="{{ old('department_name', $settings['department_name']) }}"
                                   placeholder="ກົມຄຸ້ມຄອງພະນັກງານ" required>
                            @error('department_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">ຂໍ້ຄວາມຕ້ອນຮັບ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-chat-heart"></i></span>
                            <input type="text" class="form-control @error('welcome_message') is-invalid @enderror"
                                   name="welcome_message"
                                   value="{{ old('welcome_message', $settings['welcome_message']) }}"
                                   placeholder="ຍິນດີຕ້ອນຮັບເຂົ້າສູ່ໂປຣແກມ">
                            @error('welcome_message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-eye me-1"></i> ເບິ່ງໜ້າຫຼັກ
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> ບັນທຶກ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-muted mt-3" style="font-size:0.85rem;">
            <i class="bi bi-info-circle me-1"></i>
            ຊື່ກົມຈະຖືກນຳໃຊ້ໃນໜ້າຫຼັກ ແລະ ຫົວເອກະສານ PDF ຂໍ້ມູນພະນັກງານ
        </div>
    </div>
</div>
@endsection
