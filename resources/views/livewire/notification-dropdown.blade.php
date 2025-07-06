{{-- resources/views/livewire/notification-dropdown.blade.php --}}
<div class="notification-container prevent-close" wire:ignore.self>
    <a href="#" onclick="toggleNotifDialog(event)" class="d-block position-relative prevent-close">
        <i class="fas fa-bell fa-lg"></i>
        @if($unreadCount > 0)
            <span class="notif-badge">{{ $unreadCount }}</span>
        @endif
    </a>

    {{-- Dialog HARUS di dalam .notification-container --}}
    <div class="notif-dialog-box d-none" id="notif-dialog" wire:ignore>
        <div class="dialog-title">
            <span>Notifikasi Hari Ini</span>
            <button class="btn-close" onclick="toggleNotifDialog(event)">&times;</button>
        </div>

        {{-- Polling setiap 5 detik pada list notifikasi --}}
        <div class="notifications-list" wire:poll.5s="loadNotifications">
            @forelse($notifications as $notification)
                <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}">
                    <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="text-decoration-none">
                        <div class="notification-content">
                            <div class="notification-text">
                                Pengajuan izin dari {{ $notification->permission->user->name }}
                            </div>
                            <small class="d-block text-muted mt-1">
                                {{ $notification->created_at->format('H:i') }} - {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </a>
                </div>
            @empty
                <p class="empty-notif">Tidak ada notifikasi hari ini</p>
            @endforelse
        </div>
        
        {{-- Footer info --}}
        @if($notifications->count() > 0)
            <div class="dialog-footer">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Menampilkan {{ $notifications->count() }} notifikasi hari ini
                </small>
            </div>
        @endif
    </div>
</div>