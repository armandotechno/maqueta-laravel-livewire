{{-- Solo renderiza el componente si realmente hay algo que mostrar --}}
@if (session('status') || session('error') || $errors->any())
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" {{-- x-cloak evita que se vea un parpadeo al cargar --}} x-cloak
        class="w-full">

        @if (session('status'))
            <flux:badge color="green" icon="check-circle" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
                {{ session('status') }}
            </flux:badge>
        @endif

        @if (session('error'))
            <flux:badge color="red" icon="exclamation-triangle"
                class="py-3 px-4 w-full justify-start shadow-sm mb-4 font-bold">
                {{ session('error') }}
            </flux:badge>
        @endif

        @if ($errors->any())
            <flux:badge color="amber" icon="information-circle" class="py-3 px-4 w-full justify-start shadow-sm mb-4">
                {{-- Aquí puedes poner el mensaje de error en español que configuramos en el controlador --}}
                Revisa los errores en el formulario.
            </flux:badge>
        @endif
    </div>
@endif
