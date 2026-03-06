{{-- MODAL CREAR USUARIO --}}
<flux:modal name="create-user" class="md:w-[450px]" x-data="{ hasErrors: {{ $errors->any() ? 'true' : 'false' }} }" x-init="if (hasErrors) $flux.modal('create-user').show()">

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf
        <div>
            <flux:heading size="lg">Registrar Nuevo Usuario</flux:heading>
            <flux:subheading>Los datos se guardarán de forma segura en la base de datos.</flux:subheading>
        </div>

        <flux:input name="name" label="Nombre Completo" :value="old('name')" required
            placeholder="Ej. Armando José" />
        <flux:input name="email" label="Correo Electrónico" type="email" :value="old('email')" required
            placeholder="usuario@mppre.gob.ve" />
        <flux:input name="password" label="Contraseña (Mín. 8 caracteres)" type="password" required />

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancelar</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary">Guardar Usuario</flux:button>
        </div>
    </form>
</flux:modal>
