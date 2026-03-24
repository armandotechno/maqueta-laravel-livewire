<?php

namespace App\Livewire; // Ajusta el namespace si lo guardas en otra subcarpeta

use Livewire\Component;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateUser extends Component
{
    public $nacionalidad = 'V';
    public $cedula = '';
    public $primer_nombre = '';
    public $segundo_nombre = '';
    public $primer_apellido = '';
    public $segundo_apellido = '';

    public $nombres_completos = '';
    public $apellidos_completos = '';
    public $sexo_completo = '';

    public $fecha_nacimiento = '';
    public $sexo = '';
    public $email = '';
    public $password = '';

    public $selectedRoles = [];
    public $selectedPermissions = [];

    public $isFound = false;
    public $loading = false;

    public function limpiarCampos()
    {
        // Limpiamos la data de la persona
        $this->primer_nombre = '';
        $this->segundo_nombre = '';
        $this->primer_apellido = '';
        $this->segundo_apellido = '';
        $this->nombres_completos = '';
        $this->apellidos_completos = '';
        $this->fecha_nacimiento = null;
        $this->sexo = null;
        $this->sexo_completo = '';

        // ¡CLAVE! Volvemos a bloquear el botón de guardar por seguridad
        $this->isFound = false;
    }

    public function buscarCedula()
    {
        // 1. Limpiamos los mensajes de la pantalla
        session()->forget(['error', 'status']);

        // 2. ¡EL TRUCO! Limpiamos todos los campos ANTES de hacer la nueva búsqueda
        $this->limpiarCampos();

        // Validamos que la cédula no esté vacía
        if (empty($this->cedula) || !ctype_digit($this->cedula)) {
            session()->flash('error', 'Ingrese un número de cédula válido.');
            return;
        }

        $this->loading = true;
        $cedulaCompleta = $this->nacionalidad . '-' . $this->cedula;

        // Revisamos primero nuestra base de datos
        if (Persona::where('cedula', $cedulaCompleta)->exists()) {
            $this->loading = false;
            session()->flash('error', 'El usuario con esta cédula ya se encuentra registrado en el sistema.');
            return;
        }

        // Procedemos a consultar la API
        try {
            $response = Http::withOptions(['verify' => false])->get("https://apicedulas.mppre.gob.ve/personas/cedulanac/{$cedulaCompleta}");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['response'])) {
                    $datosPersona = $data['response'];
                    $fechaNacimientoLocal = $datosPersona['fecha_nac'] ?? null;

                    // Validamos la edad
                    if ($fechaNacimientoLocal) {
                        $edad = \Carbon\Carbon::parse($fechaNacimientoLocal)->age;

                        if ($edad < 18) {
                            session()->flash('error', 'La persona debe ser mayor de edad (tiene ' . $edad . ' años).');
                            return; // Ya limpiamos los campos arriba, así que solo salimos
                        }
                    }

                    // Si pasa todo, llenamos los campos
                    $this->primer_nombre = $datosPersona['primer_nombre'] ?? '';
                    $this->segundo_nombre = $datosPersona['segundo_nombre'] ?? '';
                    $this->primer_apellido = $datosPersona['primer_apellido'] ?? '';
                    $this->segundo_apellido = $datosPersona['segundo_apellido'] ?? '';

                    $this->nombres_completos = trim($this->primer_nombre . ' ' . $this->segundo_nombre);
                    $this->apellidos_completos = trim($this->primer_apellido . ' ' . $this->segundo_apellido);

                    $this->fecha_nacimiento = $fechaNacimientoLocal;
                    $this->sexo = $datosPersona['sexo'] ?? null;

                    if ($this->sexo === 'M') {
                        $this->sexo_completo = 'Masculino';
                    } elseif ($this->sexo === 'F') {
                        $this->sexo_completo = 'Femenino';
                    } else {
                        $this->sexo_completo = 'No definido';
                    }

                    // Encendemos el botón de guardar
                    $this->isFound = true;
                    session()->flash('status', 'Datos de identificación obtenidos correctamente.');
                } else {
                    session()->flash('error', 'Estructura de datos no válida.');
                }
            } else {
                session()->flash('error', 'La cédula no se encuentra registrada en el sistema nacional.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo establecer conexión con el servicio de verificación.');
        }

        $this->loading = false;
    }

    public function guardar()
    {
        $cedulaFinal = $this->nacionalidad . '-' . $this->cedula;

        if (Persona::where('cedula', $cedulaFinal)->exists()) {
            session()->flash('error', 'El usuario con esta cédula ya se encuentra registrado en el sistema.');
            return;
        }

        $rules = [
            'cedula' => 'required|numeric',
            'email' => 'required|string|email:rfc,dns|min:6|max:255|unique:usuarios,email',
            'password' => 'required|string|min:8',
            'selectedRoles' => 'required|array|min:1',
            'selectedPermissions' => 'required|array|min:1',
        ];

        $messages = [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un formato de correo válido.',
            'email.unique' => 'Este correo ya está registrado en el sistema.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'cedula.required' => 'El número de cédula es obligatorio.',
            'selectedRoles.required' => 'Debe seleccionar al menos un rol.',
            'selectedPermissions.required' => 'Debe asignar al menos un permiso.',
        ];

        $this->validate($rules, $messages);

        try {
            DB::transaction(function () use ($cedulaFinal) {
                $fechaSQL = null;
                if ($this->fecha_nacimiento) {
                    $fechaSQL = Carbon::createFromFormat('d-m-Y', $this->fecha_nacimiento)->format('Y-m-d');
                }

                $persona = Persona::create([
                    'nacionalidad' => $this->nacionalidad,
                    'cedula' => $cedulaFinal,
                    'primer_nombre' => $this->primer_nombre,
                    'segundo_nombre' => $this->segundo_nombre,
                    'primer_apellido' => $this->primer_apellido,
                    'segundo_apellido' => $this->segundo_apellido,
                    'fecha_nacimiento' => $fechaSQL,
                    'sexo' => $this->sexo,
                ]);

                $user = User::create([
                    'persona_id' => $persona->id,
                    'name' => "{$this->primer_nombre} {$this->primer_apellido}",
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);

                if (!empty($this->selectedRoles)) {
                    $user->syncRoles($this->selectedRoles);
                }

                if (!empty($this->selectedPermissions)) {
                    $user->syncPermissions($this->selectedPermissions);
                }
            });

            return redirect()->route('admin.users')->with('status', '¡Usuario creado con éxito!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error de sistema: ' . $e->getMessage());
            // session()->flash('error', 'Ocurrió un error inesperado al procesar el registro. Por favor, contacte al administrador del sistema.');
        }
    }

    public function render()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $permissions = \Spatie\Permission\Models\Permission::all();

        return view('livewire.create-user', compact('roles', 'permissions'));
    }
}
