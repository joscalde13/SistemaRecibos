<x-layouts::auth :title="'Iniciar sesión'">
    <div class="flex flex-col gap-6">
        <div class="flex justify-center">
            <img src="{{ asset('assets/logo/logo.jpeg') }}" alt="Logo" class="h-40 w-auto object-contain" />
        </div>

        <x-auth-header :title="'Inicia sesión en tu cuenta'" :description="'Ingresa tu correo y contraseña para iniciar sesión'" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="'Correo electrónico'"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="correo@ejemplo.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="'Contraseña'"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="'Contraseña'"
                    viewable
                />

               
            </div>

          
            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    Iniciar sesión
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>¿No tienes una cuenta?</span>
            <flux:link :href="route('register')" wire:navigate>Regístrate</flux:link>
        </div>
    </div>
</x-layouts::auth>
