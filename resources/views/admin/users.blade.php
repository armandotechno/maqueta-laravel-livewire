<x-layouts::app :title="__('Gestión de Usuarios')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4">
        {{-- Encabezado --}}
        <div class="flex items-center justify-between px-2">
            <div>
                <flux:heading size="xl">Gestión de Usuarios</flux:heading>
                <flux:subheading>Administra personal y sus permisos de acceso.</flux:subheading>
            </div>
            <flux:modal.trigger name="create-user">
                <flux:button variant="primary" icon="plus">Nuevo Usuario</flux:button>
            </flux:modal.trigger>
        </div>

        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nombre</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Roles Actuales</flux:table.column>
                    <flux:table.column class="w-20">Acciones</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach (\App\Models\User::with('roles')->latest()->get() as $user)
                        <flux:table.row>
                            <flux:table.cell font="medium">{{ $user->name }}</flux:table.cell>
                            <flux:table.cell class="text-neutral-500">{{ $user->email }}</flux:table.cell>
                            <flux:table.cell>
                                @foreach ($user->getRoleNames() as $role)
                                    <flux:badge size="sm" color="zinc" inset="top bottom">{{ $role }}
                                    </flux:badge>
                                @endforeach
                                </flux:cell>
                                <flux:table.cell>
                                    <flux:dropdown>
                                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                        <flux:menu>
                                            {{-- Este botón abre el modal de roles --}}
                                            <flux:modal.trigger name="edit-roles-{{ $user->id }}">
                                                <flux:menu.item icon="shield-check">Asignar Roles</flux:menu.item>
                                            </flux:modal.trigger>

                                            <flux:menu.separator />

                                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                                onsubmit="return confirm('¿Eliminar usuario?')">
                                                @csrf @method('DELETE')
                                                <flux:menu.item type="submit" variant="danger" icon="trash">Eliminar
                                                </flux:menu.item>
                                            </form>
                                        </flux:menu>
                                    </flux:dropdown>
                                    </flux:cell>
                        </flux:table.row>

                        {{-- MODAL DE ROLES PARA CADA USUARIO --}}
                        <flux:modal name="edit-roles-{{ $user->id }}" class="md:w-[400px]">
                            <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}"
                                class="space-y-6">
                                @csrf @method('PUT')
                                <div>
                                    <flux:heading size="lg">Gestionar Roles: {{ $user->name }}</flux:heading>
                                    <flux:subheading>Selecciona los roles que tendrá este usuario en el sistema.
                                    </flux:subheading>
                                </div>

                                <div class="space-y-3">
                                    @foreach (\Spatie\Permission\Models\Role::all() as $role)
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                            <flux:label>{{ ucfirst($role->name) }}</flux:label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex gap-2">
                                    <flux:spacer />
                                    <flux:modal.close>
                                        <flux:button variant="ghost">Cancelar</flux:button>
                                    </flux:modal.close>
                                    <flux:button type="submit" variant="primary">Actualizar Roles</flux:button>
                                </div>
                            </form>
                        </flux:modal>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </div>

    {{-- Modal de Creación (Igual que antes) --}}
    <flux:modal name="create-user" class="md:w-[450px]">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf
            <flux:heading size="lg">Registrar Nuevo Usuario</flux:heading>
            <flux:input name="name" label="Nombre Completo" required />
            <flux:input name="email" label="Correo Electrónico" type="email" required />
            <flux:input name="password" label="Contraseña" type="password" required />
            <div class="flex gap-2">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>
</x-layouts::app>
