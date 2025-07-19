<header class="navbar navbar-expand-md navbar-dark sticky-top shadow-sm" style="background-color: #002b5b;">
    <div class="container-fluid">
        <button class="navbar-toggler border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand d-flex align-items-center ms-3" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo PLN"
                style="height: 40px; width: 40px; object-fit: contain; margin-right: 10px;">
            <span class="fw-semibold" style="font-size: 1.1rem; color: #ffc107;">ABSENSI PLN</span>
        </a>

        <div class="d-flex ms-auto align-items-center pe-3">
            <form action="{{ route('auth.logout') }}" method="POST"
                onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-light ms-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</header>
