@extends('layouts.auth')

@push('style')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@section('content')

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="container text-center">
        <div class="mx-auto bg-white shadow-sm p-4 rounded" 
             style="min-width: 300px; max-width: 400px; width: 100%;">
            <img src="../assets/images/logo.jpg" width="180" alt="">
            <form method="POST" action="{{ route('auth.login') }}" id="login-form" class="needs-validation mt-5" novalidate="">
                @csrf
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInputEmail" name="email"
                           placeholder="name@example.com" required>
                    <label for="floatingInputEmail">Alamat email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword" name="password"
                           placeholder="Password" required>
                    <label for="floatingPassword">Kata Sandi</label>
                </div>
                <button class="w-100 btn btn-primary" type="submit" id="login-form-button">Login</button>
                <p class="mt-3 mb-0 text-muted">Copyright &copy; 2025</p>
            </form>
        </div>
    </div>
</body>

@endsection

@push('script')
<script type="module" src="{{ asset('js/auth/login.js') }}"></script>
@endpush