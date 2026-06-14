{{-- resources/views/employees/edit.blade.php --}}
@extends('layouts.mainLayout')

@push('styles')
<style>
    .section-card { border-radius: 12px; margin-bottom: 1.25rem; }
    .section-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
</style>
@endpush

@section('content')
<div class="py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">ແກ້ໄຂຂໍ້ມູນພະນັກງານ</h4>
        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> ກັບຄືນ
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('employees.update', $employee) }}"
          method="POST"
          enctype="multipart/form-data"
          id="employeeForm"
          novalidate>
        @csrf
        @method('PUT')

        {{-- ... all the form fields from edit_form_blade.php ... --}}

    </form>
</div>
@endsection

{{-- Paste the @push('scripts') block from edit_scripts_blade.php here --}}
