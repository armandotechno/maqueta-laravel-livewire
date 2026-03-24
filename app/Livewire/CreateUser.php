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

    public $isFound = false;
    public $loading = false;

    public function limpiarCampos()
    {
        $this->primer_nombre = '';
        $this->segundo_nombre = '';
        $this->primer_apellido = '';
        $this->segundo_apellido = '';
        $this->nombres_completos = '';
        $this->apellidos_completos = '';
        $this->fecha_nacimiento = null;
        $this->sexo = null;
        $this->sexo_completo = '';
    }

    public function buscarCedula()
    {
        session()->forget(['error', 'status']);

        if (empty($this->cedula) || !ctype_digit($this->cedula)) {
            session()->flash('error', 'Ingrese un número de cédula válido.');
            return;
        }

        $this->loading = true;
        $cedulaCompleta = $this->nacionalidad . '-' . $this->cedula;

        if (Persona::where('cedula', $cedulaCompleta)->exists()) {
            $this->isFound = false;
            $this->loading = false;
            session()->flash('error', 'El usuario con esta cédula ya se encuentra registrado en el sistema.');
            return;
        }

        try {
            $response = Http::withOptions(['verify' => false])->get("https://apicedulas.mppre.gob.ve/personas/cedulanac/{$cedulaCompleta}");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['response'])) {
                    $datosPersona = $data['response'];
                    $fechaNacimientoLocal = $datosPersona['fecha_nac'] ?? null;

                    if ($fechaNacimientoLocal) {
                        $edad = \Carbon\Carbon::parse($fechaNacimientoLocal)->age;

                        if ($edad < 18) {
                            $this->isFound = false;
                            session()->flash('error', 'La persona debe ser mayor de edad (tiene ' . $edad . ' años).');
                            $this->limpiarCampos();
                            return;
                        }
                    }

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

                    $this->isFound = true;
                    session()->flash('status', 'Datos de identificación obtenidos correctamente.');
                } else {
                    $this->isFound = false;
                    session()->flash('error', 'Estructura de datos no válida.');
                }
            } else {
                $this->isFound = false;
                session()->flash('error', 'La cédula no se encuentra registrada en el sistema nacional.');
            }
        } catch (\Exception $e) {
            $this->isFound = false;
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
        ];

        $messages = [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un formato de correo válido.',
            'email.unique' => 'Este correo ya está registrado en el sistema.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'cedula.required' => 'El número de cédula es obligatorio.',
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

                User::create([
                    'persona_id' => $persona->id,
                    'name' => "{$this->primer_nombre} {$this->primer_apellido}",
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);
            });

            return redirect()->route('admin.users')->with('status', '¡Usuario creado con éxito!');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error inesperado al procesar el registro. Por favor, contacte al administrador del sistema.');
        }
    }

    // NUEVO: Esta función conecta la clase con la vista
    public function render()
    {
        return view('livewire.create-user');
    }
}
