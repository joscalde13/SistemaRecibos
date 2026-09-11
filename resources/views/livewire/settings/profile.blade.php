<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading level="2" class="sr-only">Configuración del perfil</flux:heading>

    <x-settings.layout :heading="'Perfil'" :subheading="'Actualiza tu nombre y correo electrónico'">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="'Nombre'" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="'Correo electrónico'" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            Tu dirección de correo no está verificada.

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                Haz clic aquí para reenviar el correo de verificación.
                            </flux:link>
                        </flux:text>

                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">Guardar</flux:button>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:settings.delete-user-form />
        @endif
    </x-settings.layout>
</section>
