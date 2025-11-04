<?php

use Livewire\Attributes\{Layout, On, Title};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.guest')]
#[Title('Registrar Empresa')]
class extends Component {
    public $selectedType = 0;

    #[On('select-company-type')]
    public function handleSelectCompanyType($type)
    {
        $this->selectedType = $type;
    }
}; ?>

<div x-data="{
        type: @entangle('selectedType')
    }"
    class="h-[calc(100vh-80px)] flex items-center justify-center relative">
    <!-- Card de selección de tipo -->
    <div x-show="type === 0"
         x-transition:enter="transition ease-out duration-500 delay-150"
         x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
         x-transition:leave-end="opacity-0 transform -translate-x-full scale-95"
         class="absolute inset-0 flex items-center justify-center">
        <livewire:company.select-type />
    </div>

    <!-- Formulario de registro -->
    <div x-show="type > 0"
         x-transition:enter="transition ease-out duration-500 delay-150"
         x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
         x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
         class="absolute inset-0 flex items-center justify-center">
        <livewire:company.register-form :companyType="$selectedType" :key="'form-'.$selectedType" />
    </div>
</div>
