<div class="flex items-center justify-center min-h-[80vh] bg-gray-50">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg border border-gray-200">

        <h2 class="text-2xl font-bold text-center text-[#03295a] uppercase mb-2">Bienvenido</h2>
        <p class="text-center text-gray-500 text-sm mb-6">Por políticas de seguridad, debes cambiar la contraseña
            temporal por una personal.</p>

        <form wire:submit.prevent="actualizarClave" class="space-y-4">

            <div>
                <label class="block font-bold text-sm text-black uppercase mb-1">Nueva Contraseña</label>
                <input type="password" wire:model="password"
                    class="w-full rounded border-2 border-black px-3 py-2 text-black focus:outline-none focus:border-[#03295a]" />
                @error('password')
                    <span class="text-red-600 text-xs font-bold">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block font-bold text-sm text-black uppercase mb-1">Repetir Contraseña</label>
                <input type="password" wire:model="password_confirmation"
                    class="w-full rounded border-2 border-black px-3 py-2 text-black focus:outline-none focus:border-[#03295a]" />
            </div>

            <button type="submit"
                class="w-full bg-[#03295a] text-white font-bold py-3 mt-4 rounded hover:bg-[#043675] uppercase transition-colors">
                Guardar y Entrar
            </button>

        </form>
    </div>
</div>
