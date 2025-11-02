<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="min-h-[calc(100vh-80px)] flex items-center justify-center py-12">
    <div class="max-w-2xl w-full mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
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
                <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-primary-500 hover:bg-primary-50 transition-colors cursor-pointer group">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-200 transition-colors">
                            <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 group-hover:text-primary-700">Empresa Natural</h4>
                    </div>
                </div>

                <!-- Empresa Jurídica -->
                <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-primary-500 hover:bg-primary-50 transition-colors cursor-pointer group" @wire:click="$dispatch('select-company-type', { type: 'juridica' })">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-200 transition-colors">
                            <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 group-hover:text-primary-700">Empresa Jurídica</h4>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-start">
                <a href="#" class="text-primary-600 hover:text-primary-700 flex items-center text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
</div>
