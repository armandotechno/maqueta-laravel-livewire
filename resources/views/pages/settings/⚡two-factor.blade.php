<?php

use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

new #[Title('Two-factor authentication')] class extends Component {
    public bool $twoFactorEnabled;

    public bool $requiresConfirmation;

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        abort_unless(Features::enabled(Features::twoFactorAuthentication()), Response::HTTP_FORBIDDEN);

        if (Fortify::confirmsTwoFactorAuthentication() && is_null(auth()->user()->two_factor_confirmed_at)) {
            $disableTwoFactorAuthentication(auth()->user());
        }

        $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
    }

    /**
     * Handle the two-factor authentication enabled event.
     */
    #[On('two-factor-enabled')]
    public function onTwoFactorEnabled(): void
    {
        $this->twoFactorEnabled = true;
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication(auth()->user());

        $this->twoFactorEnabled = false;
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Two-factor authentication settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Autenticación de dos factores')" :subheading="__('Administrar su configuración de autenticación de dos factores')">
        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            @if ($twoFactorEnabled)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="green">{{ __('Activado') }}</flux:badge>
                    </div>

                    <flux:text>
                        {{ __('Con la autenticación de dos factores habilitada, se le solicitará un PIN aleatorio seguro durante el inicio de sesión, que puede recuperar de la aplicación compatible con TOTP en su teléfono.') }}
                    </flux:text>

                    <livewire:pages::settings.two-factor.recovery-codes :$requiresConfirmation />

                    <div class="flex justify-start">
                        <flux:button variant="danger" icon="shield-exclamation" icon:variant="outline"
                            wire:click="disable">
                            {{ __('Desactivar 2FA') }}
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="red">{{ __('Desactivada') }}</flux:badge>
                    </div>

                    <flux:text variant="subtle">
                        {{ __('Al activar la autenticación de dos factores, se le solicitará un PIN seguro al iniciar sesión. Puede obtenerlo desde una aplicación compatible con TOTP en su teléfono.') }}
                    </flux:text>

                    <flux:modal.trigger name="two-factor-setup-modal">
                        <flux:button variant="primary" icon="shield-check" icon:variant="outline"
                            wire:click="$dispatch('start-two-factor-setup')">
                            {{ __('Activar 2FA') }}
                        </flux:button>
                    </flux:modal.trigger>

                    <livewire:pages::settings.two-factor-setup-modal :requires-confirmation="$requiresConfirmation" />
                </div>
            @endif
        </div>
    </x-pages::settings.layout>
</section>
