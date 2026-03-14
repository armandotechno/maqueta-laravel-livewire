<flux:modal name="create-user" class="md:w-[450px] !bg-white !text-black" x-data="{ hasErrors: {{ $errors->any() ? 'true' : 'false' }} }" x-init="if (hasErrors) $flux.modal('create-user').show()">

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf

        <flux:heading size="lg" class="!text-black mb-4">Nuevo Usuario</flux:heading>

        <flux:field>
            <flux:label class="font-bold !text-black">Nombre</flux:label>
            <flux:input wire:model="name" name="name" :value="old('name')" required
                class="!bg-blue-50 !border-2 !border-black !rounded-lg focus:!ring-2 focus:!ring-blue-500"
                style="color: black !important;" />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label class="font-bold !text-black">Email</flux:label>
            <flux:input wire:model="email" name="email" type="email" :value="old('email')" required
                class="!bg-blue-50 !border-2 !border-black !rounded-lg focus:!ring-2 focus:!ring-blue-500"
                style="color: black !important;" />
            <flux:error name="email" />
        </flux:field>

        <flux:field>
            <flux:label class="font-bold !text-black">Contraseña</flux:label>
            <flux:input wire:model="password" name="password" type="password" required
                class="!bg-blue-50 !border-2 !border-black !rounded-lg focus:!ring-2 focus:!ring-blue-500"
                style="color: black !important;" />
            <flux:error name="password" />
        </flux:field>

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="primary" color="red" class="font-bold">Cancelar</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary"
                class="!bg-[#03295a] hover:!bg-[#043675] !text-white border-none shadow-sm transition-colors">
                Guardar</flux:button>
        </div>
    </form>
</flux:modal>
