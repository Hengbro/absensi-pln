<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse custom-sidebar">
    <div class="position-sticky pt-3">
        <div class="text-center mb-3">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo"
                style="height: 80px; width: 80px; object-fit: contain;">
        </div>
        <ul class="nav flex-column">
            @if (auth()->user()->isAdmin() or auth()->user()->isOperator())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}" aria-current="page"
                    href="{{ route('dashboard.index') }}">
                    <span data-feather="home" class="align-text-bottom"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}"
                    href="{{ route('positions.index') }}">
                    <span data-feather="tag" class="align-text-bottom"></span>
                    Kelola Jabatan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                    href="{{ route('employees.index') }}">
                    <span data-feather="users" class="align-text-bottom"></span>
                    Data Karyawaan
                </a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('holidays.*') ? 'active' : '' }}"
                    href="{{ route('holidays.index') }}">
                    <span data-feather="calendar" class="align-text-bottom"></span>
                    Hari Libur
                </a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}"
                    href="{{ route('attendances.index') }}">
                    <span data-feather="clipboard" class="align-text-bottom"></span>
                    Adminstarsi Presensi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('presences.*') ? 'active' : '' }}"
                    href="{{ route('presences.index') }}">
                    <span data-feather="clipboard" class="align-text-bottom"></span>
                    Data Presensi
                </a>
            </li>
            @endif
        </ul>

        <form action="{{ route('auth.logout') }}" method="post"
            onsubmit="return confirm('Apakah anda yakin ingin keluar?')">
            @method('DELETE')
            @csrf
            <button class="w-full mt-4 d-block bg-transparent border-0 fw-bold text-danger px-3">Logout</button>
        </form>
    </div>
</nav>