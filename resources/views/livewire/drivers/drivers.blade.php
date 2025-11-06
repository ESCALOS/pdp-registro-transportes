<?php

use App\Models\Driver;
use App\Enums\DriverStatusEnum;
use App\Livewire\Forms\DriverForm;
use Livewire\Attributes\{Layout, Title, Computed};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

new
#[Layout('components.layouts.dashboard')]
#[Title('Gestión de Conductores')]

class extends Component {
    use WithPagination, WithFileUploads;
    
    public DriverForm $form;
    public $search = '';
    public $perPage = 10;
    public $statusFilter = '';
    public $showModal = false;
    
    // Tab control
    public $activeTab = 'personal';
    
    public function mount()
    {
        $this->initializeDocuments();
    }
    
    public function initializeDocuments()
    {
        $this->form->documents = [
            'dni' => null,
            'licencia' => null,
            'pbip' => null,
            'seg_portuaria' => null,
            'merc_peligrosas' => null,
            'sctr' => null,
            'induc' => null,
            'decla' => null,
        ];
        
        $this->form->document_dates = [
            'dni' => '',
            'licencia' => '',
            'pbip' => '',
            'seg_portuaria' => '',
            'merc_peligrosas' => '',
            'sctr' => '',
            'induc' => '',
            'decla' => '',
        ];
    }
    
    #[Computed]
    public function drivers()
    {
        return Driver::with(['company', 'documents'])
            ->where('company_id', auth()->user()->company_id)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('document_number', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%')
                      ->orWhere('lastname', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }
    
    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->form->reset();
        $this->activeTab = 'personal';
        $this->initializeDocuments();
        $this->resetValidation();
    }
    
    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedPerPage()
    {
        $this->resetPage();
    }
    
    public function updatedStatusFilter()
    {
        $this->resetPage();
    }
    
    public function checkDuplicate()
    {
        \Log::info('checkDuplicate called with: ' . $this->form->document_number);
        
        $this->resetErrorBag('form.document_number');
        
        if (strlen($this->form->document_number) >= 8) {
            $exists = Driver::where('company_id', auth()->user()->company_id)
                ->where('document_number', $this->form->document_number)
                ->exists();
            
            \Log::info('Driver exists: ' . ($exists ? 'YES' : 'NO'));
            
            if ($exists) {
                $this->addError('form.document_number', 'Ya existe un conductor registrado con este número de documento en esta empresa.');
            }
        }
    }

    
    public function save()
    {
        \Log::info('Save method called');
        
        try {
            \Log::info('Calling form->save()');
            $driver = $this->form->save();
            
            \Log::info('Driver saved: ' . $driver->id);
            
            $this->closeModal();
            $this->dispatch('driver-created');
            session()->flash('message', 'Conductor registrado exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Save error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            $this->addError('save', 'Error al registrar el conductor: ' . $e->getMessage());
        }
    }
    
    public function getStatusClass($status)
    {
        return match($status) {
            DriverStatusEnum::ACTIVE => 'status-approved',
            DriverStatusEnum::INACTIVE => 'status-rejected',
            DriverStatusEnum::NEEDS_UPDATE => 'status-update',
            DriverStatusEnum::PENDING_APPROVAL => 'status-pending',
            DriverStatusEnum::DOCUMENT_REVIEW => 'status-review',
            DriverStatusEnum::INFECTED_DOCUMENTS => 'status-infected',
            default => 'status-wait',
        };
    }
    
    public function getStatusLabel($status)
    {
        return match($status) {
            DriverStatusEnum::ACTIVE => '✓ Aprobado (Activo)',
            DriverStatusEnum::INACTIVE => '✕ Rechazado (Inactivo)',
            DriverStatusEnum::NEEDS_UPDATE => '⚠ Actualizar (Inactivo)',
            DriverStatusEnum::PENDING_APPROVAL => '… Espera de aprobación',
            DriverStatusEnum::DOCUMENT_REVIEW => '… Revisión Documentos',
            DriverStatusEnum::INFECTED_DOCUMENTS => '✕ Documentos Infectados (Inactivo)',
            default => '… Espera de aprobación',
        };
    }
};
?>

@push('styles')
<style>
/* Drivers Module Styles */
.page-title {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: space-between !important;
    margin-bottom: 20px;
    gap: 20px;
    width: 100%;
}

.page-title > * {
    flex-shrink: 0;
}

.page-title h3 {
    margin: 0 !important;
    font-weight: 800;
    font-size: 1.4rem;
    color: #111;
}

.card-app {
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    border: 1px solid #e5e7eb;
    background: white;
}

.search-input {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 10px 14px;
    font-size: 0.95rem;
}

.search-input:focus {
    border-color: #8b2d20;
    box-shadow: 0 0 0 3px rgba(139, 45, 32, 0.1);
    outline: none;
}

/* Enhanced Search Box */
.filters-row {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.search-box-container {
    flex: 1;
    min-width: 300px;
}

.search-box-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 14px;
    color: #9ca3af;
    pointer-events: none;
    z-index: 1;
}

.search-input-enhanced {
    width: 100%;
    padding: 12px 16px 12px 44px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.95rem;
    color: #111;
    background: #f9fafb;
    transition: all 0.2s ease;
}

.search-input-enhanced::placeholder {
    color: #9ca3af;
}

.search-input-enhanced:focus {
    outline: none;
    border-color: #8b2d20;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(139, 45, 32, 0.08);
}

.search-input-enhanced:focus + .search-icon,
.search-input-enhanced:not(:placeholder-shown) + .search-icon {
    color: #8b2d20;
}

.filter-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.filter-select {
    padding: 11px 14px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9rem;
    color: #111;
    background: #f9fafb;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 160px;
}

.filter-select:focus {
    outline: none;
    border-color: #8b2d20;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(139, 45, 32, 0.08);
}

.filter-select:hover {
    border-color: #d1d5db;
}

/* Status Pills */
.status-pill {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.85rem;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

.status-update {
    background: #fef3c7;
    color: #92400e;
}

.status-pending {
    background: #f3f4f6;
    color: #4b5563;
}

.status-review {
    background: #dbeafe;
    color: #1e40af;
}

.status-infected {
    background: #fee2e2;
    color: #991b1b;
}

.status-wait {
    background: #f3f4f6;
    color: #6b7280;
}

.status-primary {
    background: #e7f0ff;
    color: #1e63d6;
}

/* Buttons */
.btn-new {
    background: #8b2d20;
    color: #fff;
    border: none;
    padding: 11px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-new .btn-icon {
    width: 18px;
    height: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    font-weight: 700;
    font-size: 1.1rem;
    line-height: 1;
}

.btn-new:hover {
    background: #a33525;
    box-shadow: 0 4px 8px rgba(139, 45, 32, 0.2);
    transform: translateY(-1px);
}

.btn-new:active {
    transform: translateY(0);
}

/* Modal Styles */
.modal-backdrop-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 1040;
}

.modal-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    overflow-y: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-dialog-custom {
    width: 100%;
    max-width: 800px;
    margin: auto;
}

.modal-content-custom {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    border: none;
}

.modal-header-custom {
    padding: 18px 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-title-custom {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111;
    margin: 0;
}

.btn-close-custom {
    background: transparent;
    border: none;
    font-size: 2rem;
    line-height: 1;
    color: #6b6b6b;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
}

.btn-close-custom:hover {
    background: #f3f4f6;
    color: #111;
}

.modal-body-custom {
    padding: 20px 24px;
}

.modal-description {
    color: #6b6b6b;
    margin-bottom: 18px;
    font-size: 0.9rem;
}

.tabs-container {
    background: #f1f3f5;
    border-radius: 8px;
    padding: 4px;
    display: flex;
    gap: 4px;
    margin-bottom: 20px;
}

.tab-button {
    flex: 1;
    padding: 10px 16px;
    border: none;
    background: transparent;
    color: #6b6b6b;
    font-weight: 500;
    font-size: 0.9rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.tab-button.active {
    background: #fff;
    color: #111;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.tab-button:hover:not(.active) {
    color: #111;
}

.tab-content-area {
    min-height: 300px;
}

.tab-pane-active {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.alert-info-box {
    background: #fee;
    border: 1px solid #fcc;
    color: #c33;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 0.875rem;
}

.form-label-custom {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
    font-size: 0.875rem;
}

.form-control-custom {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.9rem;
    color: #111;
    transition: all 0.2s;
}

.form-control-custom:focus {
    outline: none;
    border-color: #8b2d20;
    box-shadow: 0 0 0 3px rgba(139, 45, 32, 0.1);
}

.form-control-custom::placeholder {
    color: #9ca3af;
}

.form-hint {
    display: block;
    margin-top: 6px;
    color: #6b7280;
    font-size: 0.85rem;
}

.docs-warning-box {
    background: #fff7ea;
    border: 1px solid #f3d7a8;
    padding: 14px;
    border-radius: 8px;
    margin-bottom: 18px;
}

.warning-title {
    font-weight: 700;
    color: #92400e;
    margin-bottom: 4px;
    font-size: 0.95rem;
}

.warning-text {
    color: #92400e;
    font-size: 0.875rem;
}

.docs-list-container {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 8px;
}

.docs-list-container::-webkit-scrollbar {
    width: 8px;
}

.docs-list-container::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.15);
    border-radius: 8px;
}

.docs-list-container::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 8px;
}

.doc-item-box {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 14px;
    margin-bottom: 12px;
}

.badge-required {
    color: #dc2626;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-optional {
    color: #6b7280;
    font-size: 0.85rem;
    font-weight: 500;
}

.modal-footer-custom {
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-cancel {
    padding: 11px 24px;
    border: 1px solid #d1d5db;
    background: #fff;
    color: #374151;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.btn-submit {
    padding: 11px 28px;
    border: none;
    background: #8b2d20;
    color: #fff;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 1px 3px rgba(139, 45, 32, 0.3);
}

.btn-submit:hover {
    background: #a33525;
    box-shadow: 0 4px 8px rgba(139, 45, 32, 0.3);
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.modal-content {
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

/* Modal Tabs */
.nav-tabs {
    background: #f1f3f5;
    border-radius: 10px;
    padding: 4px;
    border-bottom: 0;
    display: flex;
    gap: 0;
}

.nav-tabs .nav-item {
    flex: 1;
}

.nav-tabs .nav-link {
    border: 0;
    border-radius: 8px;
    color: #6b6b6b;
    background: transparent;
    margin: 0;
    width: 100%;
    text-align: center;
    padding: 10px 16px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.nav-tabs .nav-link.active {
    background: #fff;
    color: #111;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.nav-tabs .nav-link:hover:not(.active) {
    color: #111;
}

/* Documents */
.docs-required {
    background: #fff7ea;
    border: 1px solid #f3d7a8;
    color: #6b4a18;
    border-radius: 8px;
    padding: 14px;
}

.docs-required .title {
    font-weight: 700;
    color: #6b4a18;
    margin-bottom: 4px;
}

.docs-required .muted {
    color: #6b4a18;
    opacity: 0.9;
}

.docs-list .doc-item {
    background: #fff;
    border-radius: 8px;
    padding: 14px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.03);
    margin-bottom: 14px;
}

.upload-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 20px;
    min-height: 68px;
    border-radius: 10px;
    border: 2px dashed rgba(139, 45, 32, 0.35);
    background: transparent;
    color: #8b2d20;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.15s ease;
}

.upload-btn:hover {
    background: rgba(139, 45, 32, 0.02);
    border-color: #8b2d20;
}

.upload-btn svg {
    width: 20px;
    height: 20px;
}

.file-name-top {
    margin-bottom: 8px;
    color: #6b6b6b;
    font-weight: 500;
}

.file-badge {
    display: block;
    font-weight: 600;
    color: #6b6b6b;
    padding: 8px 6px;
    background: transparent;
    border-radius: 6px;
    margin-top: 8px;
}

.file-badge.ok {
    color: #1e63d6;
}

.remove-btn {
    background: transparent;
    border: 0;
    color: #b94a4a;
    font-weight: 700;
    cursor: pointer;
    margin-left: 8px;
}

.docs-list {
    max-height: calc(60vh);
    overflow: auto;
    padding-right: 8px;
}

.docs-list::-webkit-scrollbar {
    width: 8px;
}

.docs-list::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.08);
    border-radius: 8px;
}

#docs-count {
    display: inline-block;
    padding: 6px 10px;
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
}

/* Table Styles */
.table {
    width: 100%;
    margin-bottom: 0;
    color: #212529;
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    background-color: #fff;
    border-bottom: 1px solid #e5e7eb;
    border-top: 1px solid #e5e7eb;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 14px 16px;
    vertical-align: middle;
    color: #374151;
    text-transform: none;
}

.table thead th:first-child {
    border-left: 1px solid #e5e7eb;
    border-top-left-radius: 8px;
}

.table thead th:last-child {
    border-right: 1px solid #e5e7eb;
    border-top-right-radius: 8px;
}

.table tbody td {
    padding: 16px;
    vertical-align: middle;
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.9rem;
    color: #111;
}

.table tbody tr:last-child td {
    border-bottom: 1px solid #e5e7eb;
}

.table tbody tr:last-child td:first-child {
    border-bottom-left-radius: 8px;
}

.table tbody tr:last-child td:last-child {
    border-bottom-right-radius: 8px;
}

.table tbody td:first-child {
    border-left: 1px solid #e5e7eb;
}

.table tbody td:last-child {
    border-right: 1px solid #e5e7eb;
}

.table-hover tbody tr:hover {
    background-color: #f9fafb;
}

.search-input {
    border: 1px solid #e5e7eb;
    padding: 10px 14px;
    font-size: 0.95rem;
}

.btn-update-docs {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
    color: white;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
}

.btn-update-docs:hover {
    background: linear-gradient(135deg, #ea580c 0%, #f59e0b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
    color: white;
}

.btn-update-docs:active {
    transform: translateY(0);
}

@media (max-width: 768px) {
    .page-title {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}
</style>
@endpush

<div>
    <div class="page-title">
        <div style="display: flex; align-items: center; gap: 14px;">
            <a href="{{ route('dashboard') }}" class="text-decoration-none" style="color:#6b6b6b;font-size:1.15rem;">←</a>
            <h3 style="margin: 0; font-weight: 800; font-size: 1.4rem;">Gestión de Conductores</h3>
        </div>
        <button type="button" wire:click="openModal" class="btn-new">
            <span class="btn-icon">+</span> Nuevo Conductor
        </button>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-app">
        <div class="card-body" style="padding: 24px;">
            <h5 class="mb-4" style="font-weight: 700; color: #111; font-size: 1.1rem;">Mis Conductores</h5>

            <div class="filters-row mb-4">
                <div class="search-box-container">
                    <div class="search-box-wrapper">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input 
                            type="search" 
                            wire:model.live.debounce.300ms="search"
                            class="search-input-enhanced" 
                            placeholder="Buscar por DNI, Licencia, Apellidos o Nombres...">
                    </div>
                </div>
                
                <div class="filter-group">
                    <div class="filter-item">
                        <label class="filter-label">Estado</label>
                        <select wire:model.live="statusFilter" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="{{ DriverStatusEnum::ACTIVE->value }}">✓ Aprobado (Activo)</option>
                            <option value="{{ DriverStatusEnum::INACTIVE->value }}">✕ Rechazado (Inactivo)</option>
                            <option value="{{ DriverStatusEnum::NEEDS_UPDATE->value }}">⚠ Actualizar (Inactivo)</option>
                            <option value="{{ DriverStatusEnum::PENDING_APPROVAL->value }}">… Espera de aprobación</option>
                            <option value="{{ DriverStatusEnum::DOCUMENT_REVIEW->value }}">… Revisión Documentos</option>
                            <option value="{{ DriverStatusEnum::INFECTED_DOCUMENTS->value }}">✕ Documentos Infectados (Inactivo)</option>
                        </select>
                    </div>
                    
                    <div class="filter-item">
                        <label class="filter-label">Mostrar</label>
                        <select wire:model.live="perPage" class="filter-select">
                            <option value="5">5 registros</option>
                            <option value="10">10 registros</option>
                            <option value="25">25 registros</option>
                            <option value="50">50 registros</option>
                            <option value="100">100 registros</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Conductor</th>
                            <th scope="col">DNI</th>
                            <th scope="col">Licencia</th>
                            <th scope="col">Estado Solicitud</th>
                            <th scope="col">Documentos</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->drivers as $driver)
                            <tr wire:key="driver-{{ $driver->id }}">
                                <td>{{ $driver->created_at->format('d/m/Y') }}</td>
                                <td class="text-uppercase">{{ $driver->full_name }}</td>
                                <td>{{ $driver->document_number }}</td>
                                <td>{{ $driver->license_number ?? '-' }}</td>
                                <td>
                                    <span class="status-pill {{ $this->getStatusClass($driver->status) }}">
                                        {{ $this->getStatusLabel($driver->status) }}
                                    </span>
                                </td>
                                <td>{{ $driver->documents->count() }} documento(s)</td>
                                <td class="text-center">
                                    @if($driver->status === DriverStatusEnum::NEEDS_UPDATE && $driver->appeal_token)
                                        <a href="{{ route('driver.appeal.show', $driver->appeal_token) }}" 
                                           class="btn-update-docs" 
                                           target="_blank">
                                            Actualizar Documentos
                                        </a>
                                    @elseif($driver->status === DriverStatusEnum::INACTIVE)
                                        <span class="text-muted">Sin acciones disponibles</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <p class="text-muted mb-0">No se encontraron conductores.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($this->drivers->hasPages())
            <div class="mt-3">
                {{ $this->drivers->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal-backdrop-custom"></div>
    <div class="modal-wrapper">
        <div class="modal-dialog-custom">
            <div class="modal-content-custom">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">Registrar Nuevo Conductor</h5>
                    <button type="button" class="btn-close-custom" wire:click="closeModal" aria-label="Cerrar">×</button>
                </div>
                <div class="modal-body-custom">
                    <p class="modal-description">Completa todos los datos del conductor. El estado iniciará como "Pendiente" para revisión del administrador.</p>

                    <div class="tabs-container">
                        <button 
                            class="tab-button {{ $activeTab === 'personal' ? 'active' : '' }}" 
                            wire:click="setTab('personal')"
                            type="button">
                            Datos Personales
                        </button>
                        <button 
                            class="tab-button {{ $activeTab === 'docs' ? 'active' : '' }}"
                            wire:click="setTab('docs')"
                            type="button">
                            Documentos y Vencimientos
                        </button>
                    </div>

                    <div class="tab-content-area">
                        <!-- Personal Data Tab -->
                        @if($activeTab === 'personal')
                        <div class="tab-pane-active">
                            <div class="alert-info-box">
                                <strong>Completa tu información.</strong> Los campos con * son obligatorios.
                            </div>
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-custom">DNI <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            wire:model="form.document_number"
                                            wire:change="checkDuplicate"
                                            class="form-control-custom @error('form.document_number') is-invalid @enderror" 
                                            placeholder="12345678"
                                            maxlength="20">
                                        <small class="form-hint">8 dígitos</small>
                                        
                                        @error('form.document_number')
                                            <div class="mt-2">
                                                <small class="text-danger d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-circle-fill me-1" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                                                    </svg>
                                                    {{ $message }}
                                                </small>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-custom">Nombres <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            wire:model="form.name"
                                            class="form-control-custom @error('form.name') is-invalid @enderror" 
                                            placeholder="Juan Carlos">
                                        @error('form.name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-custom">Apellidos <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            wire:model="form.lastname"
                                            class="form-control-custom @error('form.lastname') is-invalid @enderror" 
                                            placeholder="Pérez García">
                                        @error('form.lastname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-custom">Licencia <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            wire:model="form.license_number"
                                            class="form-control-custom @error('form.license_number') is-invalid @enderror" 
                                            placeholder="LIC-123456">
                                        @error('form.license_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Documents Tab -->
                        @if($activeTab === 'docs')
                        <div class="tab-pane-active">
                            <div class="docs-warning-box">
                                <div class="d-flex align-items-start" style="gap:12px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12" y2="16" />
                                    </svg>
                                    <div>
                                        <div class="warning-title">Documentos requeridos</div>
                                        <div class="warning-text">PDF, JPG o PNG (máx 10MB). Ingresa fechas de vencimiento.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="docs-list-container">
                                @foreach([
                                    ['key' => 'dni', 'label' => 'DNI, Pasaporte o Carnet de Extranjería', 'required' => true],
                                    ['key' => 'licencia', 'label' => 'Licencia de conducir', 'required' => true],
                                    ['key' => 'pbip', 'label' => 'Certificado de Curso Básico PBIP I', 'required' => true],
                                    ['key' => 'seg_portuaria', 'label' => 'Certificado del Curso Básico de Seguridad Portuaria', 'required' => true],
                                    ['key' => 'merc_peligrosas', 'label' => 'Certificado del Curso Básico de Mercancías Peligrosas', 'required' => false],
                                    ['key' => 'sctr', 'label' => 'SCTR (Salud y Pensión)', 'required' => true],
                                    ['key' => 'induc', 'label' => 'Inducción de seguridad y medio ambiente virtual', 'required' => true],
                                    ['key' => 'decla', 'label' => 'Declaración Jurada de no poseer Antecedentes', 'required' => true],
                                ] as $doc)
                                    <div class="doc-item-box">
                                        <div class="row">
                                            <div class="col-md-7 mb-3">
                                                <label class="form-label-custom">
                                                    {{ $doc['label'] }} 
                                                    @if($doc['required'])
                                                        <span class="badge-required">● Obligatorio</span>
                                                    @else
                                                        <span class="badge-optional">○ Opcional</span>
                                                    @endif
                                                </label>
                                                <input 
                                                    type="file" 
                                                    wire:model="form.documents.{{ $doc['key'] }}"
                                                    class="form-control-custom" 
                                                    accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="form-hint">PDF, JPG o PNG (máx 10MB)</small>
                                            </div>
                                            <div class="col-md-5 mb-3">
                                                <label class="form-label-custom">Fecha de vencimiento</label>
                                                <input 
                                                    type="date" 
                                                    wire:model="form.document_dates.{{ $doc['key'] }}"
                                                    class="form-control-custom">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer-custom">
                    @error('save')
                        <div class="alert alert-danger w-100 mb-2">{{ $message }}</div>
                    @enderror
                    
                    <button type="button" class="btn-cancel" wire:click="closeModal">Cancelar</button>
                    <button 
                        type="button" 
                        class="btn-submit" 
                        wire:click="save"
                        onclick="console.log('Button clicked')"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Registrar</span>
                        <span wire:loading>Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
