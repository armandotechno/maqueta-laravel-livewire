<x-layouts::auth :title="__('Iniciar sesión')">
    <div class="flex flex-col gap-6">
        {{-- Forzamos el texto a blanco para contraste en fondo oscuro --}}
        <x-auth-header :title="__('Inicia sesión en tu cuenta')" class="!text-white font-bold" :description="__('Introduce tu correo electrónico y contraseña a continuación para iniciar sesión.')" class="!text-white/80" />

        <x-auth-session-status class="text-center font-medium !text-white" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:field>
                {{-- Forzamos el label a blanco --}}
                <flux:label class="font-bold !text-white text-sm uppercase">{{ __('Correo electrónico') }}</flux:label>
                {{-- Input: Fondo blanco y texto negro sólido --}}
                <input name="email" :value="old('email')" type="email" required autofocus autocomplete="email"
                    placeholder="usuario@correo.com"
                    class="w-full mt-2 rounded-md bg-white border-2 border-black text-black placeholder-gray-400 px-3 py-2 shadow-sm focus:ring-0 focus:outline-none focus:border-black"
                    maxlength="50" />
            </flux:field>

            <flux:field>
                <div class="flex justify-between">
                    {{-- Forzamos el label a blanco --}}
                    <flux:label class="font-bold !text-white text-sm uppercase">{{ __('Contraseña') }}</flux:label>
                </div>

                <input name="password" type="password" required autocomplete="current-password"
                    :placeholder="__('Contraseña')" viewable {{-- Forzamos visibilidad de icono y colores --}}
                    class="w-full mt-2 rounded-md bg-white border-2 border-black text-black placeholder-gray-400 px-3 py-2 shadow-sm focus:ring-0 focus:outline-none focus:border-black"
                    x-on:input="$el.value = $el.value.replace(/[^a-zA-Z0-9@._-]/g, '')" maxlength="50" />
            </flux:field>

            @if ($errors->has('email') || $errors->has('password'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity.duration.500ms
                    class="mb-6 bg-white border-2 border-red-600 rounded-lg p-4 shadow-md flex items-center gap-3">

                    <div class="flex-shrink-0 bg-red-100 p-2 rounded-full">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-red-600 uppercase tracking-wide">
                            Error de Autenticación
                        </p>
                        <p class="text-sm text-red-600 font-medium mt-1">
                            Las credenciales ingresadas no coinciden con nuestros registros.
                        </p>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-center pt-2">
                <flux:button type="submit"
                    class="w-full h-10 !bg-white hover:!bg-zinc-100 !text-black border-none font-bold shadow-md transition-all rounded-lg uppercase tracking-wider text-xs"
                    data-test="login-button">
                    {{ __('Acceder') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
