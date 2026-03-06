{{-- Tabla de Usuarios --}}
<div
    class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Nombre</flux:table.column>
            <flux:table.column>Email</flux:table.column>
            <flux:table.column>Roles</flux:table.column>
            <flux:table.column class="w-20">Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach (\App\Models\User::with('roles')->latest()->get() as $user)
                <flux:table.row>
                    <flux:table.cell font="medium" class="whitespace-nowrap">{{ $user->name }}
                    </flux:table.cell>
                    <flux:table.cell class="text-neutral-500">{{ $user->email }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-wrap gap-1">
                            @forelse($user->getRoleNames() as $role)
                                <flux:badge size="sm" color="zinc" inset="top bottom">
                                    {{ ucfirst($role) }}</flux:badge>
                            @empty
                                <span class="text-xs text-neutral-400 italic">Sin asignar</span>
                            @endforelse
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                            <flux:menu>
                                <flux:modal.trigger name="edit-roles-{{ $user->id }}">
                                    <flux:menu.item icon="shield-check">Asignar Roles</flux:menu.item>
                                </flux:modal.trigger>
                                <flux:menu.separator />
                                <flux:modal.trigger name="delete-user-{{ $user->id }}">
                                    <flux:menu.item variant="danger" icon="trash">Eliminar</flux:menu.item>
                                </flux:modal.trigger>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>

                {{-- MODAL DE ROLES --}}
                <flux:modal name="edit-roles-{{ $user->id }}" class="md:w-[400px]">
                    <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}" class="space-y-6">
                        @csrf @method('PUT')
                        <flux:heading size="lg">Roles: {{ $user->name }}</flux:heading>
                        <div class="space-y-3">
                            @foreach (\Spatie\Permission\Models\Role::all() as $role)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                        class="rounded border-neutral-300 text-neutral-900 shadow-sm focus:ring-neutral-500"
                                        {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium">{{ ucfirst($role->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="flex gap-2 pt-4">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button variant="ghost">Cancelar</flux:button>
                            </flux:modal.close>
                            <flux:button type="submit" variant="primary">Actualizar</flux:button>
                        </div>
                    </form>
                </flux:modal>

                {{-- MODAL DE ELIMINACIÓN --}}
                <flux:modal name="delete-user-{{ $user->id }}" class="md:w-[400px]">
                    <div class="space-y-6 text-center">
                        <div class="flex justify-center">
                            <div class="rounded-full bg-red-100 p-3 dark:bg-red-900/30">
                                <flux:icon.exclamation-triangle class="size-8 text-red-600" />
                            </div>
                        </div>
                        <flux:heading size="lg">¿Eliminar a {{ $user->name }}?</flux:heading>
                        <div class="flex gap-2">
                            <flux:modal.close class="flex-1">
                                <flux:button variant="ghost" class="w-full">Cancelar</flux:button>
                            </flux:modal.close>
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="flex-1">
                                @csrf @method('DELETE')
                                <flux:button type="submit" variant="danger" class="w-full">Eliminar
                                </flux:button>
                            </form>
                        </div>
                    </div>
                </flux:modal>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
</div>
