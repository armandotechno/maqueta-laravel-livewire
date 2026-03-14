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
                            class="rounded border-gray-400 text-black shadow-sm focus:ring-black" {{-- hasDirectPermission verifica si el usuario tiene el permiso sin importar el rol --}}
                            {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                        <span class="text-sm font-medium !text-black">{{ ucfirst($permission->name) }}</span>
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
