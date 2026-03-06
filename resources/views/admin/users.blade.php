<x-layouts::app :title="__('Gestión de Usuarios')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4">

        {{-- Alertas --}}
        @include('admin.users.partials.alerts')

        {{-- Encabezado --}}
        <div class="flex items-center justify-between px-2">
            <div>
                <flux:heading size="xl">Gestión de Usuarios</flux:heading>
                <flux:subheading>Administración de personal y roles del MPPRE.</flux:subheading>
            </div>

            <flux:modal.trigger name="create-user">
                <flux:button variant="primary" icon="plus">Nuevo Usuario</flux:button>
            </flux:modal.trigger>
        </div>

        {{-- Tabla de Usuarios --}}
        @include('admin.users.partials.table')

        {{-- Modal para Crear Usuario --}}
        @include('admin.users.partials.create-modal')
</x-layouts::app>
