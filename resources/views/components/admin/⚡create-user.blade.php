<?php

use Livewire\Component;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

new class extends Component {
    public $nacionalidad = 'V';
    public $cedula = '';
    public $primer_nombre = '';
    public $segundo_nombre = '';
    public $primer_apellido = '';
    public $segundo_apellido = '';

    // Variables para mostrar en la vista
    public $nombres_completos = '';
    public $apellidos_completos = '';
    public $sexo_completo = '';

    // Variables reales para la Base de Datos
    public $fecha_nacimiento = '';
    public $sexo = '';
    public $email = '';
    public $password = '';

    public $isFound = false;
    public $loading = false;

    public function buscarCedula()
    {
        session()->forget(['error', 'status']);

        if (empty($this->cedula) || !ctype_digit($this->cedula)) {
            session()->flash('error', 'Ingrese un número de cédula válido.');
            return;
        }

        $this->loading = true;
        $cedulaCompleta = $this->nacionalidad . '-' . $this->cedula;

        try {
            $response = Http::withOptions(['verify' => false])->get("https://apicedulas.mppre.gob.ve/personas/cedulanac/{$cedulaCompleta}");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['response'])) {
                    $datosPersona = $data['response'];

                    $this->primer_nombre = $datosPersona['primer_nombre'] ?? '';
                    $this->segundo_nombre = $datosPersona['segundo_nombre'] ?? '';
                    $this->primer_apellido = $datosPersona['primer_apellido'] ?? '';
                    $this->segundo_apellido = $datosPersona['segundo_apellido'] ?? '';

                    // Unimos nombres y apellidos para el input de visualización
                    $this->nombres_completos = trim($this->primer_nombre . ' ' . $this->segundo_nombre);
                    $this->apellidos_completos = trim($this->primer_apellido . ' ' . $this->segundo_apellido);

                    // Guardamos los datos originales
                    $this->fecha_nacimiento = $datosPersona['fecha_nac'] ?? null;
                    $this->sexo = $datosPersona['sexo'] ?? null;

                    // Convertimos M/F a texto completo para la vista
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
                session()->flash('error', 'La cédula no se encuentra registrada en el sistema.');
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
                    'sexo' => $this->sexo, // Enviamos 'M' o 'F' a la base de datos
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
}; ?>

<div>
    <flux:modal name="create-user" class="md:w-[500px] !bg-white !text-black">
        <div class="space-y-6">
            <flux:heading size="lg" class="!text-[#03295a] text-center font-bold uppercase tracking-tight">
                Registro de Personal
            </flux:heading>

            {{-- Alertas internas del Modal con Auto-Cierre --}}
            @if (session()->has('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.opacity
                    class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if (session()->has('status'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.opacity
                    class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Zona de Búsqueda --}}
            <div class="p-4 bg-slate-50 rounded-xl border-2 border-dashed border-slate-300">
                <flux:label class="font-bold !text-black text-xs uppercase mb-2">Identificación (V/E - Número)
                </flux:label>
                <div class="flex items-center gap-2">
                    <flux:select wire:model="nacionalidad" class="!w-24 !bg-white !border-black !border-2 !text-black">
                        <flux:select.option value="V">V</flux:select.option>
                        <flux:select.option value="E">E</flux:select.option>
                    </flux:select>

                    <flux:input wire:model="cedula" placeholder="30434609"
                        x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                        class="flex-1 !bg-white !border-black !border-2 !text-black shadow-sm" />

                    <flux:button wire:click="buscarCedula" wire:loading.attr="disabled"
                        class="!bg-[#03295a] hover:!bg-[#043675] !text-white h-10 px-4 font-bold border-none transition-all">
                        <span wire:loading.remove>VERIFICAR</span>
                        <span wire:loading>...</span>
                    </flux:button>
                </div>
                @error('cedula')
                    <span class="text-red-600 font-medium text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <form wire:submit.prevent="guardar" class="space-y-4">
                {{-- Bloque de Nombres y Apellidos Completos --}}
                <div class="grid grid-cols-2 gap-3 opacity-90">
                    <flux:field>
                        <flux:label class="!text-black text-[10px] font-bold uppercase">Nombres</flux:label>
                        <flux:input wire:model="nombres_completos" readonly
                            class="!bg-gray-100 !text-black cursor-not-allowed" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="!text-black text-[10px] font-bold uppercase">Apellidos</flux:label>
                        <flux:input wire:model="apellidos_completos" readonly
                            class="!bg-gray-100 !text-black cursor-not-allowed" />
                    </flux:field>
                </div>

                {{-- Bloque de Fecha y Género --}}
                <div class="grid grid-cols-2 gap-3 opacity-90">
                    <flux:field>
                        <flux:label class="!text-black text-[10px] font-bold uppercase">Fecha de Nac.</flux:label>
                        <flux:input wire:model="fecha_nacimiento" readonly
                            class="!bg-gray-100 !text-black cursor-not-allowed" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="!text-black text-[10px] font-bold uppercase">Género</flux:label>
                        <flux:input wire:model="sexo_completo" readonly
                            class="!bg-gray-100 !text-black cursor-not-allowed" />
                    </flux:field>
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-4">
                    <flux:field>
                        <flux:label class="!text-black font-bold">Correo Electrónico</flux:label>
                        <flux:input wire:model="email" type="email" placeholder="usuario@correo.com"
                            class="!bg-blue-50 !border-2 !border-black !text-black" />
                        <flux:error name="email" class="text-red-600 font-medium text-xs mt-1" />
                    </flux:field>

                    <flux:field>
                        <flux:label class="!text-black font-bold">Contraseña</flux:label>
                        <flux:input wire:model="password" type="password"
                            class="!bg-blue-50 !border-2 !border-black !text-black" />
                        <flux:error name="password" class="text-red-600 font-medium text-xs mt-1" />
                    </flux:field>
                </div>

                <div class="flex gap-2 pt-6">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost" class="!text-black border border-gray-300">Cerrar</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" :disabled="!$isFound"
                        class="!bg-[#03295a] hover:!bg-[#043675] !text-white border-none shadow-lg transition-all {{ !$isFound ? 'opacity-30 cursor-not-allowed hover:!bg-[#03295a]' : '' }}">
                        REGISTRAR FUNCIONARIO
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
