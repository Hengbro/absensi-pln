<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Models\User;

class ChangePasswordForm extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
        'new_password_confirmation' => 'required',
    ];

    protected $messages = [
        'current_password.required' => 'Password lama wajib diisi.',
        'new_password.required' => 'Password baru wajib diisi.',
        'new_password.min' => 'Password baru minimal 8 karakter.',
        'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        'new_password_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
    ];

    public function updatePassword()
    {
        $this->validate();

        if (!Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'Password lama tidak cocok.');
            return;
        }

        // Menggunakan Query Builder
        User::where('id', auth()->id())->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('success', 'Password berhasil diubah.');
        $this->emit('password-changed');
        // redirect()->route('employees.index')->with('success', 'Password berhasil diubah.');
    }

    public function render()
    {
        return view('livewire.change-password-form');
    }
}
