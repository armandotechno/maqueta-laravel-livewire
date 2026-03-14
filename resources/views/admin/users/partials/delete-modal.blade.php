<flux:modal name="delete-user-{{ $user->id }}" class="md:w-[400px] !bg-white !text-black">
    <div class="space-y-6 text-center">
        <div class="flex justify-center">
            {{-- Círculo de advertencia --}}
            <div class="rounded-full bg-red-100 p-3">
                <flux:icon.exclamation-triangle class="size-8 text-red-600" />
            </div>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg" class="!text-black">
                ¿Eliminar a {{ $user->name }}?
            </flux:heading>
            <p class="text-sm text-gray-500">
                Esta acción no se puede deshacer. Se eliminarán permanentemente los datos del usuario
                <b>{{ $user->email }}</b>.
            </p>
        </div>

        <div class="flex gap-3">
            <flux:modal.close class="flex-1">
                <flux:button variant="primary" color="red" class="w-full border-gray-200">
                    Cancelar
                </flux:button>
            </flux:modal.close>

            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="flex-1">
                @csrf
                @method('DELETE')
                <flux:button type="submit" variant="primary" color="#03295a"
                    class="!bg-[#03295a] hover:!bg-[#043675] !text-white border-none shadow-sm transition-colors">
                    Confirmar Eliminación
                </flux:button>
            </form>
        </div>
    </div>
</flux:modal>
