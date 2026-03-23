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
                <flux:input name="email" :value="old('email')" type="email" required autofocus autocomplete="email"
                    placeholder="usuario@correo.com"
                    class="!bg-white !border-black !border-2 !text-black shadow-sm placeholder:!text-gray-400 opacity-100"
                    maxlength="50" />
                <flux:error name="email" class="!text-white" />
            </flux:field>

            <flux:field>
                <div class="flex justify-between">
                    {{-- Forzamos el label a blanco --}}
                    <flux:label class="font-bold !text-white text-sm uppercase">{{ __('Contraseña') }}</flux:label>
                </div>
                {{-- Input: Fondo blanco, texto negro sólido, icono visible --}}
                <flux:input name="password" type="password" required autocomplete="current-password"
                    :placeholder="__('Contraseña')" viewable {{-- Forzamos visibilidad de icono y colores --}}
                    class="!bg-white !border-black !border-2 !text-black shadow-sm placeholder:!text-gray-400 opacity-100 flux-control-icon:!text-black"
                    x-on:input="$el.value = $el.value.replace(/[^a-zA-Z0-9@._-]/g, '')" maxlength="50" />
                <flux:error name="password" class="!text-white" />
            </flux:field>

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
