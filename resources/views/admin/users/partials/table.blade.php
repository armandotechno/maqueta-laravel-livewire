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


                @include('admin.users.partials.edit-modal')
                @include('admin.users.partials.delete-modal')
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
</div>
