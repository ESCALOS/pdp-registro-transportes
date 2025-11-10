<?php

use App\Livewire\Forms\CompanyForm;
use Livewire\Attributes\{Layout, On, Title};
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new
#[Layout('components.layouts.guest')]
#[Title('Registrar Empresa')]
class extends Component {
    use WithFileUploads;

    public CompanyForm $form;
    public int $companyType = 0;
    public int $currentStep = 1;

    public function mount($companyType = 0)
    {
        $this->companyType = $companyType;
        $this->form->type = $companyType;
    }

    protected function showSwal(string $icon, string $title, string $text)
    {
        $this->js(<<<JS
            Swal.fire({
                icon: '$icon',
                title: '$title',
                text: '$text',
                confirmButtonText: 'Aceptar'
            });
        JS);
    }

    public function updatedCompanyType($value)
    {
        $this->form->type = $value;
    }

    public function nextStep()
    {
        // Validar paso actual antes de avanzar
        $this->validateCurrentStep();

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    private function validateCurrentStep()
    {
        $this->form->validateStep($this->currentStep);
    }

    public function save()
    {
        $this->form->validate();
        try {
            [$company, $user] = $this->form->save();


            $this->redirectIntended(default: route('login', absolute: false), navigate: true);
            $this->showSwal('success', '¡Éxito!', 'Empresa '. $company->business_name .' registrada exitosamente. Su solicitud está pendiente de aprobación.');
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al registrar la empresa: ' . $e->getMessage());
        }
    }
}; ?>


<div class="max-w-4xl w-full mx-auto px-4 sm:px-0">
    <!-- Card del formulario -->
    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-8">
        <!-- Header del formulario -->
        <div class="text-center mb-6 sm:mb-8">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                Registro de Empresa {{ $companyType === 1 ? 'Natural' : 'Jurídica' }}
            </h3>
            <p class="text-sm sm:text-base text-gray-600">Complete todos los campos requeridos</p>
        </div>

        <!-- Indicador de pasos -->
        <div class="flex justify-center mb-6 sm:mb-8 overflow-x-auto">
            <div class="flex items-center space-x-2 sm:space-x-4 min-w-max px-4 sm:px-0">
                <!-- Paso 1 -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full {{ $currentStep >= 1 ? 'bg-primary-600 text-white' : 'bg-gray-300 text-gray-600' }} font-medium text-xs sm:text-sm">
                        1
                    </div>
                    <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium {{ $currentStep >= 1 ? 'text-primary-600' : 'text-gray-500' }} hidden xs:inline">Empresa</span>
                </div>

                <!-- Separador -->
                <div class="w-4 sm:w-8 h-0.5 {{ $currentStep > 1 ? 'bg-primary-600' : 'bg-gray-300' }}"></div>

                <!-- Paso 2 -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full {{ $currentStep >= 2 ? 'bg-primary-600 text-white' : 'bg-gray-300 text-gray-600' }} font-medium text-xs sm:text-sm">
                        2
                    </div>
                    <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium {{ $currentStep >= 2 ? 'text-primary-600' : 'text-gray-500' }} hidden xs:inline">Representante</span>
                </div>

                <!-- Separador -->
                <div class="w-4 sm:w-8 h-0.5 {{ $currentStep > 2 ? 'bg-primary-600' : 'bg-gray-300' }}"></div>

                <!-- Paso 3 -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full {{ $currentStep >= 3 ? 'bg-primary-600 text-white' : 'bg-gray-300 text-gray-600' }} font-medium text-xs sm:text-sm">
                        3
                    </div>
                    <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium {{ $currentStep >= 3 ? 'text-primary-600' : 'text-gray-500' }} hidden xs:inline">Documentos</span>
                </div>
            </div>
        </div>

        <form wire:submit="save">
            <div class="relative overflow-hidden" style="min-height: 350px;">
                <!-- Paso 1: Datos de la Empresa -->
                <div class="absolute inset-0 transition-all duration-500 ease-in-out {{ $currentStep === 1 ? 'opacity-100 transform translate-x-0' : 'opacity-0 transform -translate-x-full' }}">
                    <div class="space-y-4 sm:space-y-6">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2 sm:mb-4">Datos de la Empresa</h4>
                            <p class="text-sm text-gray-600 mb-4 sm:mb-6">Ingrese la información básica de su empresa</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <!-- RUC -->
                            <div>
                                <label for="ruc" class="block text-sm font-medium text-gray-700 mb-1">
                                    RUC <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="ruc"
                                       wire:model="form.ruc"
                                       placeholder="{{ $companyType === 1 ? '10XXXXXXXXX' : '20XXXXXXXXX' }}"
                                       maxlength="11"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('form.ruc')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Razón Social -->
                            <div>
                                <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Razón Social <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="business_name"
                                       wire:model="form.business_name"
                                       placeholder="Nombre de la empresa"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('form.business_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 2: Datos del Representante -->
                <div class="absolute inset-0 transition-all duration-500 ease-in-out {{ $currentStep === 2 ? 'opacity-100 transform translate-x-0' : ($currentStep > 2 ? 'opacity-0 transform -translate-x-full' : 'opacity-0 transform translate-x-full') }}">
                    <div class="space-y-4 sm:space-y-6">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2 sm:mb-4">Datos del Representante</h4>
                            <p class="text-sm text-gray-600 mb-4 sm:mb-6">Información del representante legal de la empresa</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6 h-68 md:h-auto overflow-y-auto">
                            <!-- DNI -->
                            <div>
                                <label for="representative_dni" class="block text-sm font-medium text-gray-700 mb-1">
                                    DNI <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="representative_dni"
                                       wire:model="form.representative_dni"
                                       placeholder="12345678"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('form.representative_dni')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nombres -->
                            <div>
                                <label for="representative_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombres <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="representative_name"
                                       wire:model="form.representative_name"
                                       placeholder="Juan Carlos"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('form.representative_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Apellidos -->
                            <div>
                                <label for="representative_last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Apellidos <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="representative_last_name"
                                       wire:model="form.representative_last_name"
                                       placeholder="Pérez García"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('form.representative_last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Correo Electrónico -->
                            <div>
                                <label for="representative_email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Correo Electrónico <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                       id="representative_email"
                                       wire:model="form.representative_email"
                                       placeholder="usuario@ejemplo.com"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('form.representative_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contraseña -->
                            <div>
                                <label for="representative_password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       id="representative_password"
                                       wire:model="form.representative_password"
                                       placeholder="Mínimo 6 caracteres"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('form.representative_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div>
                                <label for="representative_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirmar Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       id="representative_password_confirmation"
                                       wire:model="form.representative_password_confirmation"
                                       placeholder="Repita la contraseña"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                @error('form.representative_password_confirmation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 3: Documentos Requeridos -->
                <div class="absolute inset-0 transition-all duration-500 ease-in-out {{ $currentStep === 3 ? 'opacity-100 transform translate-x-0' : 'opacity-0 transform translate-x-full' }}">
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Documentos Requeridos</h4>
                            <p class="text-sm text-gray-600 mb-6">Sube los documentos necesarios para completar el registro</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 max-h-60 overflow-y-auto">
                            <!-- Ficha RUC -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Ficha RUC <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed @error('form.ruc_document') border-red-500 @else border-gray-300 @enderror rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <x-icon.file class="w-6 h-6 text-gray-500" />
                                    </div>
                                    <input type="file"
                                           wire:model="form.ruc_document"
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           class="hidden"
                                           id="ruc_document">
                                    <label for="ruc_document" class="cursor-pointer">
                                        <span class="text-sm font-medium text-gray-700">Haga clic para seleccionar archivo</span><br>
                                        <span class="text-xs text-gray-500">PNG, JPG o PDF (máx. 5MB)</span>
                                    </label>
                                    <div wire:loading.flex wire:target="form.ruc_document" class="mt-2">
                                        <x-icon.gear-spinner class="w-4 h-4 text-primary-600 -ml-1 mr-2 animate-spin" />
                                        <span class="text-xs text-primary-600">Subiendo...</span>
                                    </div>
                                    <div wire:loading.remove wire:target="form.ruc_document">
                                        @if($form->ruc_document)
                                            <p class="mt-2 text-sm text-green-600">{{ $form->ruc_document->getClientOriginalName() }}</p>
                                        @else
                                            <p class="mt-2 text-xs text-gray-400">No se ha seleccionado archivo</p>
                                        @endif
                                    </div>
                                </div>
                                @error('form.ruc_document')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- DNI del Representante -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    DNI del Representante <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed @error('form.representative_dni_document') border-red-500 @else border-gray-300 @enderror rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <x-icon.card-id class="w-6 h-6 text-gray-500" />
                                    </div>
                                    <input type="file"
                                           wire:model="form.representative_dni_document"
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           class="hidden"
                                           id="representative_dni_document">
                                    <label for="representative_dni_document" class="cursor-pointer">
                                        <span class="text-sm font-medium text-gray-700">Haga clic para seleccionar archivo</span><br>
                                        <span class="text-xs text-gray-500">PNG, JPG o PDF (máx. 5MB)</span>
                                    </label>
                                    <div wire:loading.flex wire:target="form.representative_dni_document" class="mt-2">
                                        <x-icon.gear-spinner class="w-4 h-4 text-primary-600 -ml-1 mr-2 animate-spin" />
                                        <span class="text-xs text-primary-600">Subiendo...</span>
                                    </div>
                                    <div wire:loading.remove wire:target="form.representative_dni_document">
                                        @if($form->representative_dni_document)
                                            <p class="mt-2 text-sm text-green-600">{{ $form->representative_dni_document->getClientOriginalName() }}</p>
                                        @else
                                            <p class="mt-2 text-xs text-gray-400">No se ha seleccionado archivo</p>
                                        @endif
                                    </div>
                                </div>
                                @error('form.representative_dni_document')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Documentos adicionales para Empresa Jurídica -->
                            @if($companyType === 2)
                                <!-- Ficha SUNARP -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Ficha SUNARP <span class="text-red-500">*</span>
                                    </label>
                                    <div class="border-2 border-dashed @error('form.sunarp_document') border-red-500 @else border-gray-300 @enderror rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <x-icon.file-contract class="w-6 h-6 text-gray-500" />
                                        </div>
                                        <input type="file"
                                               wire:model="form.sunarp_document"
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               class="hidden"
                                               id="sunarp_document">
                                        <label for="sunarp_document" class="cursor-pointer">
                                            <span class="text-sm font-medium text-gray-700">Haga clic para seleccionar archivo</span><br>
                                            <span class="text-xs text-gray-500">PNG, JPG o PDF (máx. 5MB)</span>
                                        </label>
                                        <div wire:loading.flex wire:target="form.sunarp_document" class="mt-2">
                                            <x-icon.gear-spinner class="w-4 h-4 text-primary-600 -ml-1 mr-2 animate-spin" />
                                            <span class="text-xs text-primary-600">Subiendo...</span>
                                        </div>
                                        <div wire:loading.remove wire:target="form.sunarp_document">
                                            @if($form->sunarp_document)
                                                <p class="mt-2 text-sm text-green-600">{{ $form->sunarp_document->getClientOriginalName() }}</p>
                                            @else
                                                <p class="mt-2 text-xs text-gray-400">No se ha seleccionado archivo</p>
                                            @endif
                                        </div>
                                    </div>
                                    @error('form.sunarp_document')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Vigencia de Poder -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Vigencia de Poder <span class="text-red-500">*</span>
                                    </label>
                                    <div class="border-2 border-dashed @error('form.power_of_attorney_document') border-red-500 @else border-gray-300 @enderror rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <x-icon.file-signature class="w-6 h-6 text-gray-500" />
                                        </div>
                                        <input type="file"
                                               wire:model="form.power_of_attorney_document"
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               class="hidden"
                                               id="power_of_attorney_document">
                                        <label for="power_of_attorney_document" class="cursor-pointer">
                                            <span class="text-sm font-medium text-gray-700">Haga clic para seleccionar archivo</span><br>
                                            <span class="text-xs text-gray-500">PNG, JPG o PDF (máx. 5MB)</span>
                                        </label>
                                        <div wire:loading.flex wire:target="form.power_of_attorney_document" class="mt-2">
                                            <x-icon.gear-spinner class="w-4 h-4 text-primary-600 -ml-1 mr-2 animate-spin" />
                                            <span class="text-xs text-primary-600">Subiendo...</span>
                                        </div>
                                        <div wire:loading.remove wire:target="form.power_of_attorney_document">
                                            @if($form->power_of_attorney_document)
                                                <p class="mt-2 text-sm text-green-600">{{ $form->power_of_attorney_document->getClientOriginalName() }}</p>
                                            @else
                                                <p class="mt-2 text-xs text-gray-400">No se ha seleccionado archivo</p>
                                            @endif
                                        </div>
                                    </div>
                                    @error('form.power_of_attorney_document')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de navegación -->
            <div class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                <div class="flex space-x-2 sm:space-x-3 w-full sm:w-auto justify-center sm:justify-start">
                    <!-- Botón Volver al selector -->
                    <button type="button"
                            x-on:click="type = 0"
                            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                        <span class="sm:hidden">Atrás</span>
                    </button>

                    <!-- Botón Anterior -->
                    @if($currentStep > 1)
                        <button type="button"
                                wire:click="previousStep"
                                class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Anterior
                        </button>
                    @endif
                </div>

                <div class="flex space-x-2 sm:space-x-3 w-full sm:w-auto justify-center sm:justify-end">
                    <!-- Botón Siguiente -->
                    @if($currentStep < 3)
                        <button type="button"
                                wire:click="nextStep"
                                class="px-6 py-2 bg-primary-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Siguiente
                        </button>
                    @endif

                    <!-- Botón Registrar -->
                    @if($currentStep === 3)
                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                                class="px-6 py-2 bg-primary-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove>Registrar Empresa</span>
                            <span wire:loading class="flex items-center">
                                <x-icon.gear-spinner class="w-4 h-4 text-white -ml-1 mr-2 animate-spin" />
                                Registrando...
                            </span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Enlace de volver al login -->
            <div class="text-center mt-6 pt-4 border-t border-gray-200">
                <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium" wire:navigate>
                    ← Volver al inicio de sesión
                </a>
            </div>
        </form>
    </div>
</div>
