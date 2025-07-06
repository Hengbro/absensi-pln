<!-- resources/views/notifications/index.blade.php -->
@extends('layouts.base')

@push('style')
<style>
    .dialog-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
    }

    .dialog-box {
        background: white;
        border-radius: 8px;
        padding: 24px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    }

    .notification-item {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    .notification-item.unread {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .notification-item a {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .notification-item small {
        display: block;
        color: #6c757d;
        font-size: 12px;
        margin-top: 4px;
    }

    .dialog-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .empty-notif {
        font-style: italic;
        color: #6c757d;
    }
</style>
@endpush

@section('base')
<div class="dialog-overlay">
    <div class="dialog-box">
        <div class="dialog-title">Notifikasi</div>

        @forelse($notifications as $notification)
            <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}">
                <a href="{{ route('notifications.read', $notification) }}">
                    Pengajuan izin dari {{ $notification->permission->user->name }}
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                </a>
            </div>
        @empty
            <p class="empty-notif">tidak ada notifikasi</p>
        @endforelse
    </div>
</div>
@endsection
