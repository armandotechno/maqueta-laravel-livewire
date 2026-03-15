<flux:modal name="edit-roles-{{ $user->id }}" class="md:w-[450px] !bg-white !text-black">
    {{-- Definimos la lógica en Alpine --}}
    <div x-data="{
        selectedRoles: {{ json_encode($user->roles->pluck('name')) }},
        rolePermissions: {
            admin: ['editar artículos', 'eliminar artículos', 'administrar usuarios'],
            user: ['editar artículos']
        },
        // Función para saber si un permiso debe mostrarse
        shouldShow(permission) {
            return this.selectedRoles.some(role => this.rolePermissions[role]?.includes(permission));
        }
    }">
        <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}" class="space-y-6">
            @csrf @method('PUT')

            <flux:heading size="lg" class="!text-black">Roles y Permisos: {{ $user->name }}</flux:heading>

            {{-- Sección de Roles --}}
            <div>
                <h3 class="text-sm font-bold border-b border-gray-300 pb-1 mb-3 !text-black">Roles</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach (\Spatie\Permission\Models\Role::all() as $role)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" x-model="selectedRoles"
                                class="rounded border-gray-400 text-black shadow-sm focus:ring-black">
                            <span class="text-sm font-medium !text-black">{{ ucfirst($role->name) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Sección de Permisos Dependientes --}}
            <div class="mt-4">
                <h3 class="text-sm font-bold border-b border-gray-300 pb-1 mb-3 !text-black">Permisos Heredados</h3>
                <div class="space-y-3 max-h-48 overflow-y-auto p-2 bg-gray-50 rounded-lg">
                    @foreach (\Spatie\Permission\Models\Permission::all() as $permission)
                        {{-- Alpine controla la visibilidad aquí --}}
                        <label x-show="shouldShow('{{ $permission->name }}')" x-transition
                            class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                class="rounded border-gray-400 text-black shadow-sm focus:ring-black"
                                {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                            <span class="text-sm font-medium !text-black">{{ $permission->name }}</span>
                        </label>
                    @endforeach

                    {{-- Mensaje si no hay roles seleccionados --}}
                    <p x-show="selectedRoles.length === 0" class="text-xs text-gray-500 italic">
                        Selecciona un rol para ver los permisos disponibles.
                    </p>
                </div>
            </div>

            <div class="flex gap-2 pt-4">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="primary" color="red">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit"
                    class="!bg-[#03295a] hover:!bg-[#043675] !text-white border-none shadow-sm transition-colors">
                    Actualizar
                </flux:button>
            </div>
        </form>
    </div>
</flux:modal>
