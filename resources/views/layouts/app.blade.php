@extends('layouts.base')

@push('style')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-header {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #dee2e6;
    }

    .main-header .gap-3 {
        gap: 1rem !important;
    }

    .main-content {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 0.125rem 0.75rem rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }

    .notification-area {
        position: relative;
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 1.5rem 1rem;
        }
    }
</style>
@endpush

@section('base')

@include('partials.navbar')

<div class="container-fluid">
    <div class="row">
        @include('partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- HEADER SECTION -->
            <div class="d-flex justify-content-between align-items-center main-header">
                <div class="d-flex align-items-center gap-3 ms-auto notification-area">
                    @livewire('notification-dropdown')
                    @yield('buttons')
                </div>
            </div>

            <!-- MAIN CONTENT WRAPPER -->
            <div class="main-content">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@endsection
