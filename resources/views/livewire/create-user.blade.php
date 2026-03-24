<div>
    <flux:modal name="create-user" class="md:w-[500px] !bg-white !text-black">
        <div class="space-y-6">
            <flux:heading size="lg" class="!text-[#03295a] text-center font-bold uppercase tracking-tight">
                Registro de Personal
            </flux:heading>

            {{-- Alertas internas del Modal con Auto-Cierre --}}
            @if (session()->has('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.opacity
                    class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if (session()->has('status'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.opacity
                    class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Zona de Búsqueda --}}
            <div class="p-4 bg-slate-50 rounded-xl border-2 border-dashed border-slate-300">
                <flux:label class="font-bold !text-black text-xs uppercase mb-2">Identificación (V/E - Número)
                </flux:label>
                <div class="flex items-center gap-2">
                    <select wire:model="nacionalidad"
                        class="w-24 rounded-md bg-white border-2 border-black text-black px-3 py-2 shadow-sm focus:!ring-0 focus:!outline-none focus:!border-black">
                        <option value="V">V</option>
                        <option value="E">E</option>
                    </select>

                    <input wire:model="cedula" placeholder="12345678"
                        x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                        class="flex-1 w-full rounded-md bg-white border-2 border-black text-black px-3 py-2 shadow-sm focus:!ring-0 focus:!outline-none focus:!border-black"
                        maxlength="8" />

                    <flux:button wire:click="buscarCedula" wire:loading.attr="disabled"
                        class="!bg-[#03295a] hover:!bg-[#043675] !text-white h-10 px-4 font-bold border-none transition-all">
                        <span wire:loading.remove>VERIFICAR</span>
                        <span wire:loading>...</span>
                    </flux:button>
                </div>
                @error('cedula')
                    <span class="text-red-600 font-medium text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <form wire:submit.prevent="guardar" class="space-y-4">
                {{-- Bloque de Nombres y Apellidos Completos --}}
                <div class="grid grid-cols-2 gap-3 opacity-90">
                    <flux:field>
                        <flux:label class="!text-black text-[10px] font-bold uppercase">Nombres</flux:label>
                        <input wire:model="nombres_completos" readonly
                            class="w-full rounded-md bg-gray-100 text-black px-3 py-2 shadow-sm cursor-not-allowed focus:!ring-0 focus:!outline-none" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="!text-black text-[10px] font-bold uppercase">Apellidos</flux:label>
                        <input wire:model="apellidos_completos" readonly
                            class="w-full rounded-md bg-gray-100 text-black px-3 py-2 shadow-sm cursor-not-allowed focus:!ring-0 focus:!outline-none" />
                    </flux:field>
                </div>

                {{-- Bloque de Fecha y Género --}}
                <div class="grid grid-cols-2 gap-3 opacity-90">
                    <flux:field>
                        <flux:label class="!text-black text-[10px] font-bold uppercase">Fecha de Nac.</flux:label>
                        <input wire:model="fecha_nacimiento" readonly
                            class="w-full rounded-md bg-gray-100 text-black px-3 py-2 shadow-sm cursor-not-allowed focus:!ring-0 focus:!outline-none" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="!text-black text-[10px] font-bold uppercase">Género</flux:label>
                        <input wire:model="sexo_completo" readonly
                            class="w-full rounded-md bg-gray-100 text-black px-3 py-2 shadow-sm cursor-not-allowed focus:!ring-0 focus:!outline-none" />
                    </flux:field>
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-4">
                    <flux:field>
                        <flux:label class="!text-black font-bold">Correo Electrónico</flux:label>
                        <input type="email" wire:model="email" placeholder="usuario@correo.com"
                            class="w-full mt-2 rounded-md border-2 border-black bg-blue-50 text-black px-3 py-2 shadow-sm focus:!ring-0 focus:!outline-none focus:!ring-offset-0 focus:!border-black" />
                        <flux:error name="email" class="text-red-600 font-medium text-xs mt-1" />
                    </flux:field>

                    <flux:field>
                        <flux:label class="!text-black font-bold">Contraseña</flux:label>
                        <input type="password" wire:model="password"
                            class="w-full mt-2 rounded-md border-2 border-black bg-blue-50 text-black px-3 py-2 shadow-sm focus:!ring-0 focus:!outline-none focus:!ring-offset-0 focus:!border-black" />
                        <flux:error name="password" class="text-red-600 font-medium text-xs mt-1" />
                    </flux:field>
                </div>

                {{-- BLOQUE DE ROLES Y PERMISOS INTEGRADO --}}
                <div class="border-t border-gray-200 pt-4" x-data="{
                    selectedRoles: @entangle('selectedRoles'),
                    selectedPermissions: @entangle('selectedPermissions'),
                    rolePermissions: {
                        admin: ['editar artículos', 'eliminar artículos', 'administrar usuarios'],
                        user: ['editar artículos']
                    },
                    shouldShow(permission) {
                        return this.selectedRoles.some(role => this.rolePermissions[role]?.includes(permission));
                    }
                }">
                    <flux:heading size="sm" class="!text-[#03295a] font-bold mb-3 uppercase tracking-tight">Roles y
                        Permisos del Sistema</flux:heading>

                    {{-- Sección de Roles --}}
                    <div class="mb-4">
                        <h3 class="text-[10px] font-bold border-b border-gray-300 pb-1 mb-2 !text-black uppercase">Roles
                            Disponibles</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($roles as $role)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" value="{{ $role->name }}" x-model="selectedRoles"
                                        class="rounded border-black text-[#03295a] shadow-sm focus:!ring-0 focus:!outline-none">
                                    <span class="text-sm font-medium !text-black">{{ ucfirst($role->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                        {{-- AVISO DE VALIDACIÓN DE ROLES --}}
                        <flux:error name="selectedRoles" class="text-red-600 font-medium text-xs mt-2" />
                    </div>

                    {{-- Sección de Permisos Dependientes --}}
                    <div>
                        <h3 class="text-[10px] font-bold border-b border-gray-300 pb-1 mb-2 !text-black uppercase">
                            Permisos Específicos</h3>
                        <div
                            class="space-y-2 max-h-40 overflow-y-auto p-3 bg-gray-50 rounded-lg border border-gray-200">
                            @foreach ($permissions as $permission)
                                <label x-show="shouldShow('{{ $permission->name }}')" x-transition
                                    class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" value="{{ $permission->name }}" x-model="selectedPermissions"
                                        class="rounded border-black text-[#03295a] shadow-sm focus:!ring-0 focus:!outline-none">
                                    <span
                                        class="text-sm font-medium !text-black">{{ ucfirst($permission->name) }}</span>
                                </label>
                            @endforeach

                            <p x-show="selectedRoles.length === 0" class="text-xs text-gray-500 italic">
                                Selecciona un rol arriba para ver los permisos disponibles.
                            </p>
                        </div>
                        {{-- AVISO DE VALIDACIÓN DE PERMISOS --}}
                        <flux:error name="selectedPermissions" class="text-red-600 font-medium text-xs mt-2" />
                    </div>
                </div>

                <div class="flex gap-2 pt-6 border-t border-gray-200">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost" class="!text-black border border-gray-300">Cerrar</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" :disabled="!$isFound"
                        class="!bg-[#03295a] hover:!bg-[#043675] !text-white border-none shadow-lg transition-all {{ !$isFound ? 'opacity-30 cursor-not-allowed hover:!bg-[#03295a]' : '' }}">
                        REGISTRAR USUARIO
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
