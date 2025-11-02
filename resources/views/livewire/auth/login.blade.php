<?php

use App\Enums\CompanyStatusEnum;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Livewire\Attributes\{Layout, Title, Validate};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.guest')]
#[Title('Iniciar Sesión - AutoGestión')]
class extends Component {
    #[Validate('required|email')]
    public $email = '';
    #[Validate('required|string')]
    public $password = '';

    public function login()
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], true)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (! (Auth::user()->companyStatus() === CompanyStatusEnum::APPROVED && Auth::user()->companyIsActive())) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('auth.inactive_company'),
            ]);
        }

        if (! Auth::user()->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('auth.inactive'),
            ]);
        }


        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="min-h-[calc(100vh-80px)] flex items-center justify-center py-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-red-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800">Sistema de Autogestión Paracas</h2>
            <p class="text-gray-600 mt-2">Ingrese sus credenciales para acceder al sistema</p>
        </div>

        <!-- Form -->
        <form wire:submit="login" class="space-y-6">
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
                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    required
                >
                @error('email')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Contraseña
                </label>
                <input
                    type="password"
                    id="password"
                    wire:model="password"
                    placeholder="Ingrese su contraseña"
                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    required
                >
            </div>

            <!-- Login Button -->
            <button
                type="submit"
                class="w-full bg-red-700 text-white py-3 px-4 rounded-md hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 font-medium"
            >
                Iniciar Sesión
            </button>

            <!-- Forgot Password Link -->
            <div class="text-center">
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    ¿Olvidó su contraseña?
                </a>
            </div>

            <!-- Register Company Link -->
            <div class="border-t pt-6 mt-6">
                <a
                    href="#"
                    class="w-full block text-center py-3 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200 font-medium"
                >
                    Registrar Empresa
                </a>
            </div>
        </form>
    </div>
</div>
