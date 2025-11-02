<?php

use Livewire\Attributes\{Layout, On, Title};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.guest')]
#[Title('Registrar Empresa')]
class extends Component {
    public $showForm = false;

    #[On('select-company-type')]
    public function handleSelectCompanyType($type)
    {

        $this->showForm = true;
    }
}; ?>

<div>
    <livewire:company.select-type />

</div>
