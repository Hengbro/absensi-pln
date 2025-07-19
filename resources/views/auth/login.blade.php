@extends('layouts.auth')

@push('style')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        background: linear-gradient(to right, #001f54, #0466c8);
        font-family: 'Poppins', sans-serif;
        color: #fff;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
    }

    .login-box {
        background: #ffffff;
        color: #000;
        border-radius: 20px;
        padding: 2.5rem;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.6s ease-in-out;
        position: relative;
    }

    .login-box::before {
        content: '';
        position: absolute;
        top: -10px;
        right: -10px;
        width: 60px;
        height: 60px;
        background: #ffc107;
        border-radius: 50%;
        z-index: 0;
        opacity: 0.3;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-logo {
        width: 120px;
        object-fit: contain;
        margin-bottom: 1.5rem;
    }

    /* Menata ikon agar tidak menabrak label/input */
    .form-floating>.fa {
        position: absolute;
        top: 50%;
        left: 1.75rem;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 4;
        pointer-events: none;
        font-size: 1rem;
    }


    .btn-primary {
        background-color: #ffc107;
        border: none;
        border-radius: 12px;
        font-weight: bold;
        color: #001f54;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #e0a800;
        color: #fff;
        transform: scale(1.02);
    }

    .copyright {
        font-size: 0.85rem;
        margin-top: 20px;
        color: #6c757d;
    }

    .form-icon {
        position: absolute;
        top: 50%;
        left: 0.75rem;
        /* default padding lebih kecil */
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
        font-size: 1rem;
        z-index: 3;
        /* kurangi agar tidak menutupi label */
    }


    .form-floating .form-control {
        padding-left: 1.5rem !important;
        /* beri ruang dari kiri agar teks tidak ketindihan */
    }
</style>
@endpush

@section('content')

<div class="login-box text-center">
    <img src="{{ asset('assets/images/logo.jpg') }}" class="login-logo" alt="Logo PLN">

    <form method="POST" action="{{ route('auth.login') }}" id="login-form" class="needs-validation mt-4" novalidate>
        @csrf

        <div class="form-floating mb-3 position-relative">
            <input type="email" class="form-control ps-5" id="floatingInputEmail" name="email"
                placeholder="name@example.com" required>
            <label for="floatingInputEmail">Alamat Email</label>
        </div>

        <div class="form-floating mb-3 position-relative">
            <input type="password" class="form-control ps-5" id="floatingPassword" name="password"
                placeholder="Password" required>
            <label for="floatingPassword">Kata Sandi</label>
        </div>

        <button class="w-100 btn btn-primary btn-lg" type="submit" id="login-form-button">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk
        </button>

        <div class="copyright text-center mt-3">
            &copy; {{ date('Y') }} PLN Absensi. All rights reserved.
        </div>
    </form>
</div>

@endsection

@push('script')
<script type="module" src="{{ asset('js/auth/login.js') }}"></script>
@endpush