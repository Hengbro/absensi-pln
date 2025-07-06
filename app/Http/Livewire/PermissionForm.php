<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use Livewire\Component;
use App\Models\Notification;
use App\Models\User;

class PermissionForm extends Component
{
    public $permission;
    public $attendanceId;

    protected $rules = [
        'permission.title' => 'required|string|min:6',
        'permission.description' => 'required|string|max:500',
    ];

    public function save()
    {
        $this->validate();

        $permission = Permission::create([
            "user_id" => auth()->id(),
            "attendance_id" => $this->attendanceId,
            "title" => $this->permission['title'],
            "description" => $this->permission['description'],
            "permission_date" => now()->toDateString()
        ]);

        // Kirim notifikasi ke semua admin
        $admins = User::where('role_id', '1')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'permission_id' => $permission->id,
                'message' => auth()->user()->name . ' mengajukan izin.',
                'is_read' => false,
            ]);
        }

        return redirect()->route('home.show', $this->attendanceId)->with('success', 'Permintaan izin sedang diproses. Silahkan tunggu...');
    }

    public function render()
    {
        return view('livewire.permission-form');
    }
}
