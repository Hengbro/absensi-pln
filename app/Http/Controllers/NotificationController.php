<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Permission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->with('permission.user', 'permission.attendance')
            ->whereDate('created_at', Carbon::today()) // Filter hanya notifikasi hari ini
            ->latest()
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        abort_if($notification->user_id !== auth()->id(), 403);

        $notification->update(['is_read' => true]);

        return redirect()->route('presences.permissions', [
            'attendance' => $notification->permission->attendance_id
        ]);
    }
}