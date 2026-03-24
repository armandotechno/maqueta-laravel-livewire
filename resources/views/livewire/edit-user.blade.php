<div>
    <flux:modal name="edit-user" class="md:w-[500px] !bg-white !text-black">

        {{-- EFECTO DE CARGA: Se muestra mientras Livewire busca los datos --}}
        <div wire:loading wire:target="loadUser" class="flex flex-col items-center justify-center py-10">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-[#03295a]"></div>
            <p class="mt-4 font-bold text-[#03295a]">Cargando datos del funcionario...</p>
        </div>

        {{-- EL FORMULARIO: Se oculta mientras carga, se muestra cuando termina --}}
        <div wire:loading.remove wire:target="loadUser" class="space-y-6">
            <flux:heading size="lg" class="!text-[#03295a] text-center font-bold uppercase tracking-tight">
                Editar Usuario
            </flux:heading>

            @if (session()->has('error'))
                <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="actualizar" class="space-y-4">
                {{-- Usuario --}}
                <flux:field>
                    <flux:label class="!text-black text-[10px] font-bold uppercase">Usuario</flux:label>
                    <input wire:model="name" readonly
                        class="w-full rounded-md bg-gray-100 text-black px-3 py-2 shadow-sm cursor-not-allowed focus:!ring-0 focus:!outline-none" />
                </flux:field>

                {{-- Correo y Contraseña (con items-start para evitar el descuadre visual) --}}
                <div class="grid grid-cols-2 gap-4 items-start border-t border-gray-200 pt-4">
                    {{-- Campo de Correo --}}
                    <flux:field>
                        <flux:label class="!text-black font-bold">Correo Electrónico</flux:label>
                        <input type="email" wire:model.blur="email"
                            class="w-full mt-2 rounded-md border-2 border-black bg-white text-black px-3 py-2 shadow-sm focus:!ring-0 focus:!outline-none focus:!border-black" />

                        {{-- Aquí es donde sale el texto rojo --}}
                        <flux:error name="email" class="text-red-600 font-bold text-xs mt-1" />
                    </flux:field>

                    {{-- Campo de Contraseña --}}
                    <flux:field>
                        <flux:label class="!text-black font-bold">Nueva Contraseña</flux:label>
                        <input type="password" wire:model.blur="password" placeholder="(Opcional)"
                            class="w-full mt-2 rounded-md border-2 border-black bg-white text-black px-3 py-2 shadow-sm focus:!ring-0 focus:!outline-none focus:!border-black" />

                        {{-- Aquí es donde sale el texto rojo --}}
                        <flux:error name="password" class="text-red-600 font-bold text-xs mt-1" />
                    </flux:field>
                </div>

                {{-- Roles y Permisos --}}
                <div class="border-t border-gray-200 pt-4" x-data="{
                    selectedRoles: @entangle('selectedRoles'),
                    selectedPermissions: @entangle('selectedPermissions'),
                    rolePermissions: { admin: ['editar artículos', 'eliminar artículos', 'administrar usuarios'], user: ['editar artículos'] },
                    shouldShow(permission) { return this.selectedRoles.some(role => this.rolePermissions[role]?.includes(permission)); }
                }">
                    <flux:heading size="sm" class="!text-[#03295a] font-bold mb-3 uppercase tracking-tight">Roles y
                        Permisos</flux:heading>

                    {{-- Roles --}}
                    <div class="mb-4">
                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($roles as $role)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" value="{{ $role->name }}" x-model="selectedRoles"
                                        class="rounded border-black text-[#03295a] shadow-sm focus:!ring-0">
                                    <span class="text-sm font-medium !text-black">{{ ucfirst($role->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                        {{-- Alerta de Validación de Roles --}}
                        <flux:error name="selectedRoles" class="text-red-600 font-bold text-xs mt-2" />
                    </div>

                    {{-- Permisos --}}
                    <div>
                        <div
                            class="space-y-2 max-h-32 overflow-y-auto p-3 bg-gray-50 rounded-lg border border-gray-200">
                            @foreach ($permissions as $permission)
                                <label x-show="shouldShow('{{ $permission->name }}')" x-transition
                                    class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" value="{{ $permission->name }}" x-model="selectedPermissions"
                                        class="rounded border-black text-[#03295a] shadow-sm focus:!ring-0">
                                    <span
                                        class="text-sm font-medium !text-black">{{ ucfirst($permission->name) }}</span>
                                </label>
                            @endforeach

                            {{-- Mensaje de ayuda si no hay rol seleccionado --}}
                            <p x-show="selectedRoles.length === 0" class="text-xs text-gray-500 italic">Selecciona un
                                rol.</p>
                        </div>
                        {{-- Alerta de Validación de Permisos --}}
                        <flux:error name="selectedPermissions" class="text-red-600 font-bold text-xs mt-2" />
                    </div>
                </div>

                <div class="flex gap-2 pt-4 border-t border-gray-200">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost" class="!text-black border border-gray-300">Cancelar</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit"
                        class="!bg-[#03295a] hover:!bg-[#043675] !text-white border-none shadow-lg transition-all">
                        ACTUALIZAR USUARIO
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
