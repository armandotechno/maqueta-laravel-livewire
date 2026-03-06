<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.500ms>

    {{-- Mensaje de Éxito --}}
    @if (session('status'))
        <flux:badge color="green" icon="check-circle" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
            {{ session('status') }}
        </flux:badge>
    @endif

    {{-- Mensaje de Error (El que necesitas ahora) --}}
    @if (session('error'))
        <flux:badge color="red" icon="exclamation-triangle" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
            {{ session('error') }}
        </flux:badge>
    @endif
</div>
