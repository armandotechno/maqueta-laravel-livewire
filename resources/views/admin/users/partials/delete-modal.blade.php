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
