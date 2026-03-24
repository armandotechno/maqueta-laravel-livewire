<div>
    <flux:modal name="delete-user" class="md:w-[400px] !bg-white !text-black">

        {{-- EFECTO DE CARGA --}}
        <div wire:loading wire:target="loadUser" class="flex flex-col items-center justify-center py-6">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
            <p class="mt-4 font-bold text-red-600">Cargando...</p>
        </div>

        {{-- CONTENIDO DEL MODAL (Se oculta mientras carga) --}}
        <div wire:loading.remove wire:target="loadUser" class="space-y-6 text-center">

            <div class="flex justify-center">
                <div class="bg-red-100 p-4 rounded-full text-red-600 border-2 border-red-200">
                    {{-- Icono de Basura SVG --}}
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
            </div>

            <div>
                <flux:heading size="lg" class="!text-black font-bold uppercase tracking-tight">¿Eliminar registro?
                </flux:heading>
                <p class="text-sm text-gray-600 mt-3">
                    Estás a punto de eliminar a <br>
                    <span class="font-bold text-red-600 text-base uppercase">{{ $userName }}</span> <br>
                    del sistema. Esta acción no se puede deshacer.
                </p>
            </div>

            @if (session()->has('error'))
                <div class="p-3 bg-red-100 text-red-700 rounded-lg text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="eliminar" class="flex gap-3 justify-center w-full pt-4 border-t border-gray-200">
                <flux:modal.close class="w-1/2">
                    <flux:button variant="ghost" class="!text-black border border-gray-300 w-full hover:!bg-gray-100">
                        Cancelar</flux:button>
                </flux:modal.close>

                <div class="w-1/2">
                    <flux:button type="submit"
                        class="w-full shadow-lg !bg-red-600 hover:!bg-red-700 !text-white border-none">
                        Sí, eliminar
                    </flux:button>
                </div>
            </form>

        </div>
    </flux:modal>
</div>
