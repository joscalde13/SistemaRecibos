<x-layouts::auth :title="'Iniciar sesión'">
    <div class="mx-auto w-full max-w-md">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5 flex justify-center">
                <img src="{{ asset('assets/logo/logo.jpeg') }}" alt="Logo" class="h-20 w-auto object-contain" />
            </div>

            <div class="mb-5 text-center">
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">Acceso</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Ingresa tus credenciales</p>
            </div>

            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

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

                <flux:input
                    name="password"
                    :label="'Contraseña'"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="'Contraseña'"
                    viewable
                />

                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    Entrar
                </flux:button>
            </form>

            
        </div>
    </div>
</x-layouts::auth>
