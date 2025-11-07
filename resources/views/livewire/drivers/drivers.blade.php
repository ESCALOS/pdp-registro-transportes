<?php

use App\Livewire\Forms\DriverForm;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

new
#[Title('Gestión de Conductores')]

class extends Component {
    use WithPagination, WithFileUploads;

    public DriverForm $form;

    public function mount()
    {
        $this->form->initializeDocuments();
    }

    public function save()
    {
        Log::info('Save method called');

        try {
            Log::info('Calling form->save()');
            $driver = $this->form->save();

            Log::info('Driver saved: ' . $driver->id);

            $this->closeModal();
            $this->dispatch('driver-created');
            session()->flash('message', 'Conductor registrado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Save error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            $this->addError('save', 'Error al registrar el conductor: ' . $e->getMessage());
        }
    }
};
?>

<div>
    @livewire('drivers.create-driver')
    @livewire('drivers.list-drivers')
</div>
