<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends Component
{
    public $password = '';
    public $password_confirmation = '';

    public function actualizarClave()
    {
        $this->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($this->password);
        $user->needs_password_change = false; // ¡Abre la puerta del middleware!
        $user->save();

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}
