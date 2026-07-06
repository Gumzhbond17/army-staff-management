@extends('layouts.mainLayout')

@section('content')
    <div class="row">
        <div class="col-12 text-center mt-5">
            <h1 class="mt-2 mb-4">{{ \App\Models\SiteSetting::get('ministry_name', 'ກະຊວງປ້ອງກັນປະເທດ') }}</h1>
            <img src="{{ asset('assets/images/lao-army-logo.png') }}" alt="logo" style="width: 200px; height: auto;">
            <h1 class="my-5">{{ \App\Models\SiteSetting::get('department_name', 'ກົມຄຸ້ມຄອງພະນັກງານ') }}</h1>
            <p class="lead">{{ \App\Models\SiteSetting::get('welcome_message', 'ຍິນດີຕ້ອນຮັບເຂົ້າສູ່ໂປຣແກມ') }}</p>
        </div>
    </div>
@endsection

