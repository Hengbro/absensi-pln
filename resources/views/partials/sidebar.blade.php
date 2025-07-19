{{-- Sidebar PLN Style --}}
<style>
    .custom-sidebar {
        background: linear-gradient(180deg, #007bff, #fcd116); /* biru ke kuning */
        min-height: 100vh;
        color: #fff;
        font-family: 'Segoe UI', sans-serif;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    }

    .custom-sidebar .nav-link {
        color: #ffffffcc;
        font-weight: 500;
        padding: 12px 20px;
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
    }

    .custom-sidebar .nav-link:hover,
    .custom-sidebar .nav-link.active {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
        border-left: 4px solid #fff;
    }

    .custom-sidebar .nav-link span[data-feather] {
        margin-right: 8px;
    }

    .custom-sidebar button.logout-button {
        width: 100%;
        text-align: left;
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        padding: 12px 20px;
        font-weight: bold;
        transition: background 0.3s ease;
    }

    .custom-sidebar button.logout-button:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #ff4d4d;
    }
</style>

<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse custom-sidebar">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo"
                style="height: 80px; width: 80px; object-fit: contain; border-radius: 50%; box-shadow: 0 0 10px #fff;">
            <h5 class="mt-2 fw-bold text-white">ABSENSI PLN</h5>
        </div>
        <ul class="nav flex-column">
            @if (auth()->user()->isAdmin() || auth()->user()->isOperator())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}"
                        href="{{ route('dashboard.index') }}">
                        <span data-feather="home"></span>
                        Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}"
                        href="{{ route('positions.index') }}">
                        <span data-feather="briefcase"></span>
                        Jabatan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                        href="{{ route('employees.index') }}">
                        <span data-feather="users"></span>
                        Karyawan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}"
                        href="{{ route('attendances.index') }}">
                        <span data-feather="calendar"></span>
                        Pengaturan Presensi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('presences.*') ? 'active' : '' }}"
                        href="{{ route('presences.index') }}">
                        <span data-feather="clipboard"></span>
                        Riwayat Presensi
                    </a>
                </li>
            @endif
        </ul>

        <form action="{{ route('auth.logout') }}" method="post"
            onsubmit="return confirm('Apakah anda yakin ingin keluar?')">
            @method('DELETE')
            @csrf
            <button class="logout-button mt-4">Keluar</button>
        </form>
    </div>
</nav>
