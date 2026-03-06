<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.500ms>

    @if (session('status'))
        <flux:badge color="green" icon="check-circle" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
            {{ session('status') }}
        </flux:badge>
    @endif

    @if (session('error'))
        <flux:badge color="red" icon="exclamation-triangle" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
            {{ session('error') }}
        </flux:badge>
    @endif

    {{-- Errores de Validación (Contraseña corta, email duplicado) --}}
    @if ($errors->any())
        <flux:badge color="amber" icon="information-circle" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
            Hay problemas con los datos ingresados. Revisa el formulario.
        </flux:badge>
    @endif
</div>
