<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationDropdown extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    protected $listeners = ['markAllAsRead' => 'markAllAsRead'];
    
    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        
        // Hitung notifikasi yang belum dibaca HANYA untuk hari ini
        $this->unreadCount = $user->notifications()
            ->where('is_read', false)
            ->whereDate('created_at', Carbon::today())
            ->count();
            
        // Ambil notifikasi HANYA untuk hari ini
        $this->notifications = $user->notifications()
            ->with(['permission.user', 'permission.attendance'])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->take(10)
            ->get();
    }

    public function markAllAsRead()
    {
        // Mark as read hanya untuk notifikasi hari ini
        Auth::user()->notifications()
            ->where('is_read', false)
            ->whereDate('created_at', Carbon::today())
            ->update(['is_read' => true]);
            
        $this->loadNotifications();
    }

    public function markAsRead($notificationId)
    {
        Notification::where('id', $notificationId)
            ->update(['is_read' => true]);
            
        $this->loadNotifications();
        
        return redirect()->route('notifications.read', $notificationId);
    }

    public function render()
    {
        return view('livewire.notification-dropdown');
    }
}