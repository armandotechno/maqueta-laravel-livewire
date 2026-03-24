{{-- Contenedor Principal de la Tabla --}}
<div
    class="relative h-full flex-1 overflow-hidden rounded-xl border p-6 dark:border-[#333333] dark:bg-[#f5f7f9] transition-colors duration-300">
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-black font-bold">Nombre</flux:table.column>
            <flux:table.column class="!text-black font-bold">Email</flux:table.column>
            <flux:table.column class="!text-black font-bold">Roles</flux:table.column>
            <flux:table.column class="w-20 !text-black font-bold text-center">Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach (\App\Models\User::with('roles')->latest()->get() as $user)
                <flux:table.row>
                    <flux:table.cell font="medium" class="!text-black">
                        {{ $user->name }}
                    </flux:table.cell>

                    <flux:table.cell class="!text-black">
                        {{ $user->email }}
                    </flux:table.cell>

                    <flux:table.cell class="!text-black">
                        <div class="flex flex-wrap gap-1">
                            @forelse($user->getRoleNames() as $role)
                                <flux:badge size="sm" color="zinc" inset="top bottom"
                                    class="!text-black border border-gray-300">
                                    {{ ucfirst($role) }}
                                </flux:badge>
                            @empty
                                <span class="text-gray-500 italic text-sm">Sin asignar</span>
                            @endforelse
                        </div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"
                                class="!text-black" />

                            <flux:menu class="!bg-white !text-black !border !border-gray-200 shadow-lg">

                                <flux:modal.trigger name="edit-user">
                                    <flux:menu.item icon="pencil" class="!text-black hover:!bg-gray-100 cursor-pointer"
                                        @click="$dispatch('load-user-data', { id: {{ $user->id }} })">
                                        Editar Usuario
                                    </flux:menu.item>
                                </flux:modal.trigger>

                                @if (auth()->id() !== $user->id)
                                    <flux:modal.trigger name="delete-user">
                                        <flux:menu.item variant="danger" icon="trash"
                                            class="!text-red-600 hover:!bg-red-50 cursor-pointer"
                                            @click="$dispatch('load-user-for-deletion', { id: {{ $user->id }} })">
                                            Eliminar
                                        </flux:menu.item>
                                    </flux:modal.trigger>
                                @else
                                    {{-- Si ES el usuario actual, mostramos un botón bloqueado y gris --}}
                                    <flux:menu.item icon="trash" class="!text-gray-400 opacity-50 cursor-not-allowed"
                                        disabled>
                                        Eliminar (Tu cuenta)
                                    </flux:menu.item>
                                @endif

                            </flux:menu>

                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>

<livewire:create-user />
<livewire:edit-user />
<livewire:delete-user />
