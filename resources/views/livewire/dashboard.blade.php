<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    function cerrarSesion()
    {
        Auth::logout();
        $this->redirectRoute('login', [], false);
    }
}; ?>

<div>
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <p>Welcome to your dashboard!</p>
    <span>User: {{ auth()->user()->email }}</span>

    <button wire:click="cerrarSesion" class="text-red-500 underline">Cerrar sesión</button>
</div>
