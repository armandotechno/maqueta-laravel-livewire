{{-- Tabla de Usuarios --}}
<div
    class="relative h-full flex-1 overflow-hidden rounded-xl border p-6 dark:border-[#333333] dark:bg-[#f5f7f9] transition-colors duration-300">
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-black">Nombre</flux:table.column>
            <flux:table.column class="!text-black">Email</flux:table.column>
            <flux:table.column class="!text-black">Roles</flux:table.column>
            <flux:table.column class="w-20 !text-black">Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach (\App\Models\User::with('roles')->latest()->get() as $user)
                <flux:table.row>
                    <flux:table.cell font="medium" class="!text-black">
                        {{ $user->name }}
                    </flux:table.cell>

                    {{-- Se cambió text-neutral-500 por text-black --}}
                    <flux:table.cell class="!text-black">
                        {{ $user->email }}
                    </flux:table.cell>

                    <flux:table.cell class="!text-black">
                        <div class="flex flex-wrap gap-1">
                            @forelse($user->getRoleNames() as $role)
                                <flux:badge size="sm" color="zinc" inset="top bottom" class="!text-black">
                                    {{ ucfirst($role) }}
                                </flux:badge>
                            @empty
                                {{-- Se cambió text-neutral-400 por text-black --}}
                                <span class="!text-black">Sin asignar</span>
                            @endforelse
                        </div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"
                                class="!text-black" />

                            {{-- Forzamos el fondo blanco y un borde gris claro para el contenedor del menú --}}
                            <flux:menu class="!bg-white !text-black !border !border-gray-200">
                                <flux:modal.trigger name="edit-roles-{{ $user->id }}">
                                    {{-- Forzamos el texto negro y un fondo gris clarito al pasar el mouse --}}
                                    <flux:menu.item icon="shield-check" class="!text-black hover:!bg-gray-100">
                                        Asignar Roles
                                    </flux:menu.item>
                                </flux:modal.trigger>



                                <flux:modal.trigger name="delete-user-{{ $user->id }}">
                                    {{-- Nota: El variant="danger" lo pone rojo automáticamente.
                     Si lo quieres estrictamente negro, agrégale class="!text-black hover:!bg-gray-100" --}}
                                    <flux:menu.item variant="danger" icon="trash"
                                        class="!text-black hover:!bg-gray-100">
                                        Eliminar
                                    </flux:menu.item>
                                </flux:modal.trigger>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>

                {{-- MODAL DE ROLES --}}
                <flux:modal name="edit-roles-{{ $user->id }}" class="md:w-[400px] !bg-white !text-black">
                    <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}" class="space-y-6">
                        @csrf @method('PUT')

                        <flux:heading size="lg" class="!text-black">Roles y Permisos: {{ $user->name }}
                        </flux:heading>

                        {{-- Sección de Roles --}}
                        <div>
                            <h3 class="text-sm font-bold border-b border-gray-300 pb-1 mb-3 !text-black">Roles</h3>
                            <div class="space-y-3">
                                @foreach (\Spatie\Permission\Models\Role::all() as $role)
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                            class="rounded border-gray-400 text-black shadow-sm focus:ring-black"
                                            {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium !text-black">{{ ucfirst($role->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Sección de Permisos Directos --}}
                        <div class="mt-4">
                            <h3 class="text-sm font-bold border-b border-gray-300 pb-1 mb-3 !text-black">Permisos
                            </h3>
                            <div class="space-y-3 max-h-48 overflow-y-auto"> {{-- Le puse scroll por si tienes muchos permisos --}}
                                @foreach (\Spatie\Permission\Models\Permission::all() as $permission)
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            class="rounded border-gray-400 text-black shadow-sm focus:ring-black"
                                            {{-- hasDirectPermission verifica si el usuario tiene el permiso sin importar el rol --}}
                                            {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                                        <span
                                            class="text-sm font-medium !text-black">{{ ucfirst($permission->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex gap-2 pt-4">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button variant="ghost" class="!text-black">Cancelar</flux:button>
                            </flux:modal.close>
                            <flux:button type="submit" variant="primary">Actualizar</flux:button>
                        </div>
                    </form>
                </flux:modal>

                {{-- MODAL DE ELIMINACIÓN --}}
                <flux:modal name="delete-user-{{ $user->id }}" class="md:w-[400px] !bg-white !text-black">
                    <div class="space-y-6 text-center">
                        <div class="flex justify-center">
                            {{-- Eliminamos la clase dark: para que el círculo siempre sea rojo claro --}}
                            <div class="rounded-full bg-red-100 p-3">
                                <flux:icon.exclamation-triangle class="size-8 text-red-600" />
                            </div>
                        </div>

                        {{-- Forzamos el texto del título a negro --}}
                        <flux:heading size="lg" class="!text-black">¿Eliminar a {{ $user->name }}?
                        </flux:heading>

                        <div class="flex gap-2">
                            <flux:modal.close class="flex-1">
                                {{-- Forzamos el botón de cancelar a negro --}}
                                <flux:button variant="ghost" class="w-full !text-black">Cancelar</flux:button>
                            </flux:modal.close>
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="flex-1">
                                @csrf @method('DELETE')
                                <flux:button type="submit" variant="danger" class="w-full">Eliminar</flux:button>
                            </form>
                        </div>
                    </div>
                </flux:modal>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
</div>
