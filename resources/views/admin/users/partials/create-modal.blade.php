<flux:modal name="create-user" class="md:w-[450px]" x-data="{ hasErrors: {{ $errors->any() ? 'true' : 'false' }} }" x-init="if (hasErrors) $flux.modal('create-user').show()">

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf
        <flux:heading size="lg">Nuevo Usuario</flux:heading>

        <flux:input name="name" label="Nombre" :value="old('name')" required />
        <flux:input name="email" label="Email" type="email" :value="old('email')" required />

        {{-- Flux mostrará el error aquí automáticamente si el nombre coincide --}}
        <flux:input name="password" label="Contraseña" type="password" required />

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancelar</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary">Guardar</flux:button>
        </div>
    </form>
</flux:modal>
