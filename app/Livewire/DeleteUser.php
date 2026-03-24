<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class DeleteUser extends Component
{
    public $userId;
    public $userName = '';

    // Escuchamos el evento que manda Alpine
    #[On('load-user-for-deletion')]
    public function loadUser($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->userName = $user->name;
    }

    public function eliminar()
    {
        // Medida de seguridad por si le dan doble clic rápido
        if (!$this->userId) return;

        if ($this->userId === auth()->id()) {
            session()->flash('error', 'Por medidas de seguridad, no puedes eliminar tu propia cuenta mientras estás en sesión.');
            return;
        }

        try {
            DB::transaction(function () {
                $user = User::findOrFail($this->userId);
                $personaId = $user->persona_id;

                $user->delete(); // Borramos al usuario de acceso

                // Borramos los datos de la persona para liberar la cédula
                if ($personaId) {
                    Persona::where('id', $personaId)->delete();
                }
            });

            return redirect()->route('admin.users')->with('status', '¡Usuario eliminado permanentemente del sistema!');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al intentar eliminar el registro.');
        }
    }

    public function render()
    {
        return view('livewire.delete-user');
    }
}
