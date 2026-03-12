@if (session('status') || session('error') || $errors->any())
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 5000)"
         x-show="show"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="w-full">

        {{-- Éxito --}}
        @if (session('status'))
            <flux:badge color="green" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
                <x-slot name="icon">
                    <flux:icon.check-circle class="text-green-950" />
                </x-slot>
                <span class="text-green-950 font-semibold ml-2">{{ session('status') }}</span>
            </flux:badge>
        @endif

        {{-- Error Crítico --}}
        @if (session('error'))
            <flux:badge color="red" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
                <x-slot name="icon">
                    <flux:icon.exclamation-triangle class="text-red-950" />
                </x-slot>
                <span class="text-red-950 font-bold ml-2">{{ session('error') }}</span>
            </flux:badge>
        @endif

        {{-- Errores de Validación --}}
        @if ($errors->any())
            <flux:badge color="amber" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
                <x-slot name="icon">
                    <flux:icon.information-circle class="text-amber-950" />
                </x-slot>
                <span class="text-amber-950 font-semibold ml-2">Revisa los errores en el formulario.</span>
            </flux:badge>
        @endif
    </div>
@endif
