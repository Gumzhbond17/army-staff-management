<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
    <div class="container">
        {{-- <a class="navbar-brand" href="{{ URL('/') }}">ລະບົບຂຶ້ນທະບຽນພະນັກງານ</a> --}}
        <a class="navbar-brand" href="{{ URL('/') }}">ເກັບກຳຂໍ້ມູນພະນັກງານລັດ</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employees.create') ? 'active' : '' }}"
                    href="{{ route('employees.create') }}">
                        <i class="bi bi-person-plus me-1"></i>ເພີ່ມຂໍ້ມູນ
                    </a>
                </li>

                @if (auth()->user()?->role === 'admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('units.*', 'ranks.*', 'staff-categories.*', 'working-statuses.*', 'provinces.*', 'districts.*') ? 'active' : '' }}"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear me-1"></i>ຈັດການຂໍ້ມູນລະບົບ
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="bi bi-person-gear me-1"></i>ຂໍ້ມູນຜູ້ໃຊ້ລະບົບ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('units.*') ? 'active' : '' }}"
                            href="{{ route('units.index') }}">
                                <i class="bi bi-pin-map me-1"></i>ຂໍ້ມູນກອງປະຈຳ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('ranks.*') ? 'active' : '' }}"
                            href="{{ route('ranks.index') }}">
                                <i class="bi bi-award me-1"></i>ຂໍ້ມູນການເລື່ອນຊັ້ນ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('staff-categories.*') ? 'active' : '' }}"
                            href="{{ route('staff-categories.index') }}">
                                <i class="bi bi-person-bounding-box me-1"></i>ຂໍ້ມູນປະເພດພະນັກງານ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('working-statuses.*') ? 'active' : '' }}"
                            href="{{ route('working-statuses.index') }}">
                                <i class="bi bi-person-exclamation me-1"></i>ຂໍ້ມູນສະຖານະເຮັດວຽກ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('provinces.*') ? 'active' : '' }}"
                            href="{{ route('provinces.index') }}">
                                <i class="bi bi-geo-alt me-1"></i>ຂໍ້ມູນແຂວງ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('districts.*') ? 'active' : '' }}"
                            href="{{ route('districts.index') }}">
                                <i class="bi bi-geo me-1"></i>ຂໍ້ມູນເມືອງ
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }}"
                    href="{{ route('employees.index') }}">
                        <i class="bi bi-person-check me-1"></i>ຄົ້ນຫາຂໍ້ມູນ
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i>ນຳເຂົ້າຂໍ້ມູນ
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i>ລາຍງານຂໍ້ມູນ
                    </a>
                </li>

            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> ຊື່ຜູ້ໃຊ້ລະບົບ
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person-square me-2"></i>ແກ້ໄຂໂປຣຟາຍ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-lock me-1"></i>
                                ປ່ຽນລະຫັດຜ່ານ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-1"></i>ອອກຈາກລະບົບ
                            </a>
                        </li>
                        {{-- Hidden logout form (place anywhere outside the nav) --}}
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
