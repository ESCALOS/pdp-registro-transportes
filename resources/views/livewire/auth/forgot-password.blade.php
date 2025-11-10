<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PasswordResetMail;
use Livewire\Attributes\{Layout, Title, Validate};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.guest')]
#[Title('Recuperar Contraseña - AutoGestión')]
class extends Component {
    #[Validate('required|email|exists:users,email')]
    public $email = '';

    public $success = false;

    public function sendResetLink()
    {
        $this->validate();

        // Generar token único
        $token = Str::random(64);

        // Guardar en la tabla password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->email],
            [
                'email' => $this->email,
                'token' => hash('sha256', $token),
                'created_at' => now(),
            ]
        );

        // Generar URL de restablecimiento
        $resetUrl = route('password.reset.show', ['token' => $token]);

        // Enviar correo
        $user = \App\Models\User::where('email', $this->email)->first();
        Mail::to($this->email)->send(new PasswordResetMail($user, $resetUrl));

        $this->success = true;
    }
}; ?>

<div class="min-h-[calc(100vh-80px)] flex items-center justify-center py-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        @if (!$success)
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800">¿Olvidó su contraseña?</h2>
                <p class="text-gray-600 mt-2">Ingrese su correo electrónico y le enviaremos un enlace para restablecer su contraseña</p>
            </div>

            <!-- Form -->
            <form wire:submit="sendResetLink" class="space-y-6">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        placeholder="usuario@ejemplo.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                    @error('email')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium"
                >
                    Enviar Enlace de Restablecimiento
                </button>

                <!-- Back to Login Link -->
                <div class="text-center border-t pt-6 mt-6">
                    <a
                        href="{{ route('login') }}"
                        wire:navigate
                        class="text-sm text-gray-600 hover:text-gray-800 hover:underline"
                    >
                        ← Volver a Iniciar Sesión
                    </a>
                </div>
            </form>
        @else
            <!-- Success Message -->
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">¡Correo Enviado!</h2>
                <p class="text-gray-600 mb-6">
                    Hemos enviado un enlace de restablecimiento de contraseña a <strong>{{ $email }}</strong>.
                </p>
                <p class="text-sm text-gray-500 mb-6">
                    Por favor, revise su bandeja de entrada y siga las instrucciones. El enlace expirará en 60 minutos.
                </p>
                <a
                    href="{{ route('login') }}"
                    wire:navigate
                    class="inline-block bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition duration-200 font-medium"
                >
                    Volver al Inicio de Sesión
                </a>
            </div>
        @endif
    </div>
</div>
