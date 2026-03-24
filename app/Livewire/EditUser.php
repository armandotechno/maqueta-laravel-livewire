<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EditUser extends Component
{
    public $userId;
    public $name = '';
    public $email = '';
    public $password = '';

    public $selectedRoles = [];
    public $selectedPermissions = [];

    // Escuchamos el evento exacto que manda Alpine
    #[On('load-user-data')]
    public function loadUser($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';

        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->selectedPermissions = $user->permissions->pluck('name')->toArray();
    }

    public function actualizar()
    {
        // 1. Reglas base (El correo es obligatorio y debe ser único)
        $rules = [
            'email' => 'required|email:rfc,dns|max:255|unique:usuarios,email,' . $this->userId,
            'selectedRoles' => 'required|array|min:1',
            'selectedPermissions' => 'required|array|min:1',
        ];

        // 2. Magia para la contraseña: Si NO está vacía, le aplicamos reglas
        if (!empty($this->password)) {
            $rules['password'] = 'min:8';
        }

        // 3. Mensajes personalizados en español
        $messages = [
            'email.required' => 'El correo no puede quedar vacío.',
            'email.email' => 'Ingresa un formato de correo válido.',
            'email.unique' => 'Este correo ya pertenece a otro usuario.',
            'password.min' => 'La nueva contraseña debe tener mínimo 8 caracteres.',
            'selectedRoles.required' => 'Debes seleccionar al menos un rol.',
            'selectedPermissions.required' => 'Debes asignar al menos un permiso.',
        ];

        // Ejecutamos la validación
        $this->validate($rules, $messages);

        try {
            $user = User::findOrFail($this->userId);
            $user->email = $this->email;

            // Si pasó la validación y hay contraseña nueva, la encriptamos y guardamos
            if (!empty($this->password)) {
                $user->password = \Illuminate\Support\Facades\Hash::make($this->password);
            }

            $user->save();

            $user->syncRoles($this->selectedRoles);
            $user->syncPermissions($this->selectedPermissions);

            return redirect()->route('admin.users')->with('status', '¡Usuario actualizado con éxito!');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al actualizar el usuario.');
        }
    }

    public function render()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('livewire.edit-user', compact('roles', 'permissions'));
    }
}
