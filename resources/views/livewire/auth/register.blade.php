<x-layouts::auth :title="'Registrarse'">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="'Crear una cuenta'" :description="'Ingresa tus datos para crear tu cuenta'" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="'Nombre'"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="'Nombre completo'"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="'Correo electrónico'"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="correo@ejemplo.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="'Contraseña'"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="'Contraseña'"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="'Confirmar contraseña'"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="'Confirmar contraseña'"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    Crear cuenta
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>¿Ya tienes una cuenta?</span>
            <flux:link :href="route('login')" wire:navigate>Iniciar sesión</flux:link>
        </div>
    </div>
</x-layouts::auth>
