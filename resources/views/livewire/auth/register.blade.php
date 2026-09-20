<x-layouts::auth :title="'Registrarse'">
    <div class="mx-auto w-full max-w-md">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5 flex justify-center">
                <img src="{{ asset('assets/logo/logo.jpeg') }}" alt="Logo" class="h-20 w-auto object-contain" />
            </div>

            <div class="mb-5 text-center">
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">Crear cuenta</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Completa tus datos</p>
            </div>

            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                @csrf

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

                <flux:input
                    name="email"
                    :label="'Correo electrónico'"
                    :value="old('email')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="correo@ejemplo.com"
                />

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

                <flux:input
                    name="password_confirmation"
                    :label="'Confirma tu contraseña'"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="'Confirma tu contraseña'"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                />

                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    Crear cuenta
                </flux:button>
            </form>

            <p class="mt-5 text-center text-sm text-zinc-600 dark:text-zinc-400">
                ¿Ya tienes una cuenta?
                <flux:link :href="route('login')" wire:navigate>Iniciar sesión</flux:link>
            </p>
        </div>
    </div>
</x-layouts::auth>
