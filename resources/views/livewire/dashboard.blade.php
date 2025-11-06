<?php

use App\Enums\{CompanyStatusEnum, DriverStatusEnum};
use App\Models\Driver;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Livewire\Attributes\{Layout, Title, Validate, Computed};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.dashboard')]

class extends Component {
    
    #[Computed]
    public function drivers_pending()
    {
        return Driver::where('company_id', auth()->user()->company_id)
            ->whereIn('status', [DriverStatusEnum::PENDING_APPROVAL, DriverStatusEnum::DOCUMENT_REVIEW])
            ->count();
    }
    
    #[Computed]
    public function drivers_approved()
    {
        return Driver::where('company_id', auth()->user()->company_id)
            ->where('status', DriverStatusEnum::ACTIVE)
            ->count();
    }
    
    #[Computed]
    public function drivers_rejected()
    {
        return Driver::where('company_id', auth()->user()->company_id)
            ->whereIn('status', [DriverStatusEnum::INACTIVE, DriverStatusEnum::NEEDS_UPDATE, DriverStatusEnum::INFECTED_DOCUMENTS])
            ->count();
    }
    
    // TODO: Add Trucks and Chassis stats when models are ready
    public $trucks_pending = 0;
    public $trucks_approved = 0;
    public $trucks_rejected = 0;
    
    public $chassis_pending = 0;
    public $chassis_approved = 0;
    public $chassis_rejected = 0;
};
?>


<div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-sm text-slate-600">Bienvenido seleccione un módulo para comenzar.</p>
    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <div class="bg-white text-slate-900 rounded-lg shadow-md p-4 flex items-start space-x-4 hover:shadow-lg transition-shadow border" style="border-color: #e9d7d4;">
            <div class="flex-shrink-0 rounded-md p-3 inline-flex items-center justify-center" style="background-color: #8B2D23; color: #ffffff;">
                <!-- driver icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="20" height="20" fill="currentColor"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3z"/><path d="M8 7a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
            </div>
            <div class="flex-1">
                <h5 class="text-lg font-semibold text-slate-900">Drivers</h5>
                <p class="text-sm text-slate-600">Gestión de conductores</p>
                <a href="@if(Route::has('drivers')){{ route('drivers') }}@else #@endif" class="mt-2 inline-block font-medium hover:underline" style="color: #8B2D23;">Acceder →</a>
            </div>
        </div>

        <div class="bg-white text-slate-900 rounded-lg shadow-md p-4 flex items-start space-x-4 hover:shadow-lg transition-shadow border" style="border-color: #e9d7d4;">
            <div class="flex-shrink-0 rounded-md p-3 inline-flex items-center justify-center" style="background-color: #8B2D23; color: #ffffff;">
                <!-- truck icon (updated) -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M3 11h11v2H3v-2zm13 0h3l3 3v3h-2a2 2 0 1 1-4 0h-8a2 2 0 1 1-4 0H2v-3l1-5h14z"/></svg>
            </div>
            <div class="flex-1">
                <h5 class="text-lg font-semibold text-slate-900">Trucks</h5>
                <p class="text-sm text-slate-600">Gestión de vehículos</p>
                <a href="@if(Route::has('trucks.index')){{ route('trucks.index') }}@else #@endif" class="mt-2 inline-block font-medium hover:underline" style="color: #8B2D23;">Acceder →</a>
            </div>
        </div>

        <div class="bg-white text-slate-900 rounded-lg shadow-md p-4 flex items-start space-x-4 hover:shadow-lg transition-shadow border" style="border-color: #e9d7d4;">
            <div class="flex-shrink-0 rounded-md p-3 inline-flex items-center justify-center" style="background-color: #8B2D23; color: #ffffff;">
                <!-- chassis icon (updated - box/cube) -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 2L3 7v10l9 5 9-5V7l-9-5zm0 2.2L18.7 7 12 10.8 5.3 7 12 4.2z"/></svg>
            </div>
            <div class="flex-1">
                <h5 class="text-lg font-semibold text-slate-900">Chassis</h5>
                <p class="text-sm text-slate-600">Registro de chasis</p>
                <a href="@if(Route::has('chassis.index')){{ route('chassis.index') }}@else #@endif" class="mt-2 inline-block font-medium hover:underline" style="color: #8B2D23;">Acceder →</a>
            </div>
        </div>
    </div>

    <h4 class="text-lg font-semibold mt-8 mb-4 text-slate-900">Solicitudes</h4>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Drivers Requests -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center mb-4">
                <div class="w-9 h-9 rounded-md flex items-center justify-center mr-3" style="background:rgba(139,45,32,0.06); color: #8B2D23;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="18" height="18" fill="currentColor"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3z"/><path d="M8 7a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-900">Drivers</div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="p-3 bg-amber-50 rounded text-center">
                    <div class="text-xl font-semibold text-amber-700">{{ $this->drivers_pending }}</div>
                    <div class="text-xs text-amber-600">Pendientes</div>
                </div>
                <div class="p-3 bg-emerald-50 rounded text-center">
                    <div class="text-xl font-semibold text-emerald-700">{{ $this->drivers_approved }}</div>
                    <div class="text-xs text-emerald-600">Aprobadas</div>
                </div>
                <div class="p-3 bg-rose-50 rounded text-center">
                    <div class="text-xl font-semibold text-rose-700">{{ $this->drivers_rejected }}</div>
                    <div class="text-xs text-rose-600">Rechazadas</div>
                </div>
            </div>
        </div>

        <!-- Trucks Requests -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center mb-4">
                <div class="w-9 h-9 rounded-md flex items-center justify-center mr-3" style="background:rgba(139,45,32,0.06); color: #8B2D23;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M3 11h11v2H3v-2zm13 0h3l3 3v3h-2a2 2 0 1 1-4 0h-8a2 2 0 1 1-4 0H2v-3l1-5h14z"/></svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-900">Trucks</div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="p-3 bg-amber-50 rounded text-center">
                    <div class="text-xl font-semibold text-amber-700">{{ $trucks_pending ?? 0 }}</div>
                    <div class="text-xs text-amber-600">Pendientes</div>
                </div>
                <div class="p-3 bg-emerald-50 rounded text-center">
                    <div class="text-xl font-semibold text-emerald-700">{{ $trucks_approved ?? 0 }}</div>
                    <div class="text-xs text-emerald-600">Aprobadas</div>
                </div>
                <div class="p-3 bg-rose-50 rounded text-center">
                    <div class="text-xl font-semibold text-rose-700">{{ $trucks_rejected ?? 0 }}</div>
                    <div class="text-xs text-rose-600">Rechazadas</div>
                </div>
            </div>
        </div>

        <!-- Chassis Requests -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center mb-4">
                <div class="w-9 h-9 rounded-md flex items-center justify-center mr-3" style="background:rgba(139,45,32,0.06); color: #8B2D23;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2L3 7v10l9 5 9-5V7l-9-5zm0 2.2L18.7 7 12 10.8 5.3 7 12 4.2z"/></svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-900">Chassis</div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="p-3 bg-amber-50 rounded text-center">
                    <div class="text-xl font-semibold text-amber-700">{{ $chassis_pending ?? 0 }}</div>
                    <div class="text-xs text-amber-600">Pendientes</div>
                </div>
                <div class="p-3 bg-emerald-50 rounded text-center">
                    <div class="text-xl font-semibold text-emerald-700">{{ $chassis_approved ?? 0 }}</div>
                    <div class="text-xs text-emerald-600">Aprobadas</div>
                </div>
                <div class="p-3 bg-rose-50 rounded text-center">
                    <div class="text-xl font-semibold text-rose-700">{{ $chassis_rejected ?? 0 }}</div>
                    <div class="text-xs text-rose-600">Rechazadas</div>
                </div>
            </div>
        </div>
    </div>    

</div>
