<x-layouts::app :title="__('Gestión de Usuarios')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 text-slate-800">

        {{-- Alertas --}}
        @include('admin.users.partials.alerts')

        {{-- Encabezado --}}
        <div class="flex items-center justify-between px-2">
            <div>
                <flux:heading size="xl" class="text-blue-900">
                    Gestión de Usuarios
                </flux:heading>

                <flux:subheading class="text-[#475569]">
                    Administración de personal y roles.
                </flux:subheading>
            </div>

            {{-- 1. EL TRIGGER: Se queda igual porque el modal dentro del componente se llama 'create-user' --}}
            <flux:modal.trigger name="create-user">
                <flux:button variant="primary" icon="plus">Nuevo Usuario</flux:button>
            </flux:modal.trigger>
        </div>

        {{-- Tabla de Usuarios --}}
        @include('admin.users.partials.table')

        {{-- 2. EL CAMBIO: Llamas al componente Livewire en lugar del partial de Blade --}}
        <livewire:admin.create-user />

    </div>
</x-layouts::app>
