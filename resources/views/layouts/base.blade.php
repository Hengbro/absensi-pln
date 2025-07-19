<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('style')
    @include('partials.styles')

    <title>Absensi</title>
</head>

<body>
    <x-toast-container />
    @auth
    <div class="notification-wrapper">
        @livewire('notification-dropdown')
    </div>
    @endauth
    @yield('base')
    @stack('script')
    @include('partials.scripts')

    <script>
        function toggleNotifDialog(event) {
            event.preventDefault();
            event.stopPropagation();

            const wrapper = event.currentTarget.closest('.notification-container');
            const dialog = wrapper.querySelector('.notif-dialog-box');

            const isHidden = dialog.classList.contains('d-none');

            // Tutup semua dialog lain
            document.querySelectorAll('.notif-dialog-box').forEach(d => {
                if (d !== dialog) d.classList.add('d-none');
            });

            // Toggle dialog saat ini
            dialog.classList.toggle('d-none', !isHidden);

            // Emit event untuk mark all as read ketika dialog dibuka
            if (isHidden) {
                Livewire.emit('markAllAsRead');
            }
        }

        let shouldCloseNotif = true;

        document.addEventListener('mousedown', function(event) {
            const isInNotif = event.target.closest('.notification-container');
            if (isInNotif) {
                shouldCloseNotif = false; // Klik di dalam area notifikasi
            } else {
                shouldCloseNotif = true; // Klik di luar
            }
        });

        document.addEventListener('click', function() {
            setTimeout(() => {
                if (shouldCloseNotif) {
                    document.querySelectorAll('.notif-dialog-box').forEach(dialog => {
                        dialog.classList.add('d-none');
                    });
                }
            }, 10); // Delay kecil agar Livewire punya waktu patching
        });

        // Event listener untuk mempertahankan dialog terbuka saat polling
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {

            });
        });
    </script>

    <script>
        window.bootstrap = bootstrap;
    </script>
    @yield('script')
</body>
</html>