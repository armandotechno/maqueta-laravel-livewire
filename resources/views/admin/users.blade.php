<x-layouts::app :title="__('Gestión de Usuarios')">
    {{-- Si quieres que cualquier texto suelto en este contenedor tenga un color por defecto, puedes agregarlo aquí (ej. text-slate-800) --}}
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 text-slate-800">

        {{-- Alertas --}}
        @include('admin.users.partials.alerts')

        {{-- Encabezado --}}
        <div class="flex items-center justify-between px-2">
            <div>
                {{-- Ejemplo 1: Cambiando el título a un azul oscuro de Tailwind --}}
                <flux:heading size="xl" class="text-blue-900">
                    Gestión de Usuarios
                </flux:heading>

                {{-- Ejemplo 2: Cambiando el subtítulo a un color hexadecimal personalizado (ej. un gris oscuro) --}}
                <flux:subheading class="text-[#475569]">
                    Administración de personal y roles.
                </flux:subheading>
            </div>

            <flux:modal.trigger name="create-user">
                <flux:button variant="primary" icon="plus">Nuevo Usuario</flux:button>
            </flux:modal.trigger>
        </div>

        {{-- Tabla de Usuarios --}}
        @include('admin.users.partials.table')

        {{-- Modal para Crear Usuario --}}
        @include('admin.users.partials.create-modal')
    </div>
</x-layouts::app>
