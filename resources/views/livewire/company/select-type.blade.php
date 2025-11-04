<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

    <div class="max-w-2xl w-full mx-auto">
        <!-- Header -->
        <div class="text-center mb-8 hidden md:block">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Registro de Empresa</h2>
            <p class="text-gray-600">Complete el formulario según el tipo de empresa</p>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Seleccione el Tipo de Empresa</h3>
                <p class="text-gray-600">Elija la categoría que corresponde a su empresa</p>
            </div>

            <!-- Options Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Empresa Natural -->
                <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-primary-500 hover:bg-primary-50 transition-all duration-300 cursor-pointer group hover:shadow-lg hover:scale-[1.02]"
                     x-on:click="setTimeout(() => { type = 1; $dispatch('select-company-type', { type: 1 }); }, 100)">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-200 transition-all duration-300">
                            <x-icon name="user" class="w-8 h-8 text-primary-600 group-hover:scale-110 transition-transform duration-300" />
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 group-hover:text-primary-700 transition-colors duration-300">Empresa Natural</h4>
                        <p class="text-sm text-gray-500 mt-2 group-hover:text-primary-600 transition-colors duration-300">Persona física o empresario individual</p>
                    </div>
                </div>

                <!-- Empresa Jurídica -->
                <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-primary-500 hover:bg-primary-50 transition-all duration-300 cursor-pointer group hover:shadow-lg hover:scale-[1.02]"
                     x-on:click="setTimeout(() => { type = 2; $dispatch('select-company-type', { type: 2 }); }, 100)">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-200 transition-all duration-300">
                            <x-icon name="building-office" class="w-8 h-8 text-primary-600 group-hover:scale-110 transition-transform duration-300" />
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 group-hover:text-primary-700 transition-colors duration-300">Empresa Jurídica</h4>
                        <p class="text-sm text-gray-500 mt-2 group-hover:text-primary-600 transition-colors duration-300">Sociedad anónima, limitada u otra forma jurídica</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-start">
                <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 flex items-center text-sm font-medium" wire:navigate>
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
