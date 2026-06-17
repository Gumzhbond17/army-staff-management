@extends('layouts.mainLayout')

@section('title', 'ໂປຣຟາຍຂອງຂ້ອຍ')

@push('styles')
<style>

    .profile-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .profile-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .profile-card-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #ede9fe;
        color: #6d28d9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .profile-card-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .profile-card-subtitle {
        font-size: 0.8rem;
        color: #9ca3af;
        margin: 2px 0 0;
    }

    .profile-card-body {
        padding: 1.5rem;
    }

    .profile-avatar-card {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 18px rgba(0,0,0,0.13);
        flex-shrink: 0;
    }

    .profile-avatar-card-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #ede9fe;
        border: 4px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #7c3aed;
        box-shadow: 0 4px 18px rgba(0,0,0,0.10);
        flex-shrink: 0;
    }

    .form-label {
        font-size: 0.82rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 5px;
    }

    .form-control, .form-control:focus {
        font-size: 0.9rem;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 9px 13px;
    }

    .form-control:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .btn-profile-save {
        background: linear-gradient(135deg, #7c3aed, #2563eb);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 9px 24px;
        font-size: 0.88rem;
        font-weight: 500;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
    }

    .btn-profile-save:hover {
        opacity: 0.88;
        transform: translateY(-1px);
        color: #fff;
    }

    .badge-role {
        font-size: 0.72rem;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .badge-admin  { background: rgba(237,233,254,0.25); color: #c4b5fd; }
    .badge-normal { background: rgba(219,234,254,0.25); color: #93c5fd; }
</style>
@endpush

@section('content')


<div class="py-4">

    {{-- ── USER PROFILE ─────────────────────────────────── --}}
    <div class="profile-card">

        <div class="profile-card-header">
            <div class="profile-card-icon">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <p class="profile-card-title">ໂປຣຟາຍຜູ້ໃຊ້</p>
                <p class="profile-card-subtitle">ຊື່ ແລະ ຮູບພາບຈະສະແດງໃນລະບົບ</p>
            </div>
        </div>

        <div class="profile-card-body">
            @if (session('profile_success'))
                <div class="alert alert-success py-2 px-3" style="border-radius:8px; font-size:0.85rem;">
                    <i class="bi bi-check-circle me-1"></i>{{ session('profile_success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Photo --}}
                <div class="mb-4 d-flex align-items-center justify-content-center gap-4">
                    <div class="text-center">
                        @if ($profile->photo)
                            <img src="{{ $profile->photo_url }}" alt="preview"
                                 class="profile-avatar-card" id="photoPreview">
                        @else
                            <div class="profile-avatar-card-placeholder" id="photoPlaceholder">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <img src="" alt="preview"
                                 class="profile-avatar-card d-none" id="photoPreview">
                        @endif
                        <p class="mt-2 mb-0 fw-semibold" style="font-size:0.9rem; color:#111827;">{{ $user->name }}</p>
                        <p class="mb-0" style="font-size:0.78rem; color:#9ca3af;">{{ $profile->job_title ?? '' }}</p>
                    </div>

                    <div>
                        <label class="form-label">ຮູບໂປຣຟາຍ</label>
                        <input type="file" name="photo" id="photoInput" accept="image/*"
                            class="form-control @error('photo') is-invalid @enderror"
                            style="max-width: 280px; font-size:0.85rem;">
                        <div class="form-text" style="font-size:0.75rem;">
                            JPG, PNG, WEBP · ສູງສຸດ 2MB
                        </div>
                        @error('photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-4">
                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label" for="name">
                            ຊື່-ນາມສະກຸນ <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label" for="email">
                            ອີເມວ <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email', $user->email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label" for="phone">ເບີໂທ</label>
                        <input type="text" name="phone" id="phone"
                            value="{{ old('phone', $profile->phone) }}"
                            placeholder="020 XXXX XXXX"
                            class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Job Title --}}
                    <div class="col-md-6">
                        <label class="form-label" for="job_title">ຕຳແໜ່ງ</label>
                        <input type="text" name="job_title" id="job_title"
                            value="{{ old('job_title', $profile->job_title) }}"
                            placeholder="ເຊັ່ນ: ເຈົ້າໜ້າທີ່ IT"
                            class="form-control @error('job_title') is-invalid @enderror">
                        @error('job_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bio --}}
                    <div class="col-12">
                        <label class="form-label" for="bio">ໝາຍເຫດ</label>
                        <textarea name="bio" id="bio"
                            class="form-control @error('bio') is-invalid @enderror"
                            placeholder="ກ່ຽວກັບທ່ານ...">{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-profile-save">
                        <i class="bi bi-check2 me-1"></i>ອັບເດດໂປຣຟາຍ
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── CHANGE PASSWORD ────────────────────────────────── --}}
    <div class="profile-card" id="change-password">
        <div class="profile-card-header">
            <div class="profile-card-icon" style="background:#fef3c7; color:#d97706;">
                <i class="bi bi-lock-fill"></i>
            </div>
            <div>
                <p class="profile-card-title">ປ່ຽນລະຫັດຜ່ານ</p>
                <p class="profile-card-subtitle">ລະຫັດຜ່ານໃໝ່ຕ້ອງຍາວຢ່າງໜ້ອຍ 8 ຕົວອັກສອນ</p>
            </div>
        </div>

        <div class="profile-card-body">
            @if (session('password_success'))
                <div class="alert alert-success py-2 px-3" style="border-radius:8px; font-size:0.85rem;">
                    <i class="bi bi-check-circle me-1"></i>{{ session('password_success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Current password --}}
                    <div class="col-12">
                        <label class="form-label" for="current_password">
                            ລະຫັດຜ່ານປັດຈຸບັນ <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="current_password" id="current_password"
                            autocomplete="current-password"
                            class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New password --}}
                    <div class="col-md-6">
                        <label class="form-label" for="password">
                            ລະຫັດຜ່ານໃໝ່ <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                            autocomplete="new-password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm --}}
                    <div class="col-md-6">
                        <label class="form-label" for="password_confirmation">
                            ຢືນຢັນລະຫັດຜ່ານໃໝ່ <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            autocomplete="new-password"
                            class="form-control">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-profile-save"
                        style="background: linear-gradient(135deg, #d97706, #b45309);">
                        <i class="bi bi-shield-lock me-1"></i>ປ່ຽນລະຫັດຜ່ານ
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.getElementById('photoInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const preview     = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
