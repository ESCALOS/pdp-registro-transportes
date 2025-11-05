<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Enums\CompanyDocumentStatusEnum;
use App\Enums\CompanyDocumentTypeEnum;
use App\Enums\CompanyTypeEnum;
use App\Filament\Resources\Companies\CompanyResource;
use App\Models\Company;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewCompany extends Page
{
    protected static string $resource = CompanyResource::class;

    protected string $view = 'filament.resources.company-resource.pages.view-company';

    public Company $record;
    public array $documentStatuses = [];
    public array $rejectionReasons = [];
    public ?string $selectedDocument = null;

    public function mount(int|string $record): void
    {
        $this->record = Company::with(['documents', 'documents.validator'])->findOrFail(json_decode($record)->id);

        // Inicializar estados de documentos
        foreach ($this->record->documents as $document) {
            $this->documentStatuses[$document->id] = $document->status->value;
            $this->rejectionReasons[$document->id] = $document->rejection_reason ?? '';
        }
    }

    public function getTitle(): string
    {
        return "Validar Documentos - {$this->record->business_name}";
    }

    public function openDocument(int $documentId): void
    {
        $document = $this->record->documents->find($documentId);
        if ($document) {
            $this->selectedDocument = $document->path;
            $this->dispatch('open-document-modal');
        }
    }

    public function approveDocument(int $documentId): void
    {
        $this->documentStatuses[$documentId] = CompanyDocumentStatusEnum::APROBADO->value;
        $this->rejectionReasons[$documentId] = '';
    }

    public function rejectDocument(int $documentId): void
    {
        $this->documentStatuses[$documentId] = CompanyDocumentStatusEnum::RECHAZADO->value;
    }

    public function resetDocument(int $documentId): void
    {
        $this->documentStatuses[$documentId] = CompanyDocumentStatusEnum::PENDIENTE->value;
        $this->rejectionReasons[$documentId] = '';
    }

    public function canApproveAll(): bool
    {
        $requiredDocuments = $this->getRequiredDocumentTypes();

        foreach ($requiredDocuments as $type) {
            $document = $this->record->documents->firstWhere('type', $type);
            if (!$document) {
                return false;
            }

            $status = $this->documentStatuses[$document->id] ?? CompanyDocumentStatusEnum::PENDIENTE->value;
            if ($status !== CompanyDocumentStatusEnum::APROBADO->value) {
                return false;
            }
        }

        return true;
    }

    public function getRequiredDocumentTypes(): array
    {
        $baseDocuments = [
            CompanyDocumentTypeEnum::RUC_RECORD,
            CompanyDocumentTypeEnum::REPRESENTATIVE_DNI,
        ];

        if ($this->record->type === CompanyTypeEnum::JURIDICA) {
            $baseDocuments[] = CompanyDocumentTypeEnum::FICHA_SUNARP;
            $baseDocuments[] = CompanyDocumentTypeEnum::POWER_OF_ATTORNEY_VALIDITY;
        }

        return $baseDocuments;
    }

    public function saveValidation(): void
    {
        try {
            DB::beginTransaction();

            $allApproved = true;
            $hasChanges = false;

            foreach ($this->record->documents as $document) {
                $newStatus = CompanyDocumentStatusEnum::from($this->documentStatuses[$document->id]);

                if ($document->status !== $newStatus) {
                    $hasChanges = true;

                    $document->update([
                        'status' => $newStatus,
                        'rejection_reason' => $newStatus === CompanyDocumentStatusEnum::RECHAZADO
                            ? $this->rejectionReasons[$document->id]
                            : null,
                        'validated_by' => Auth::id(),
                        'validated_date' => now(),
                    ]);
                }

                if ($newStatus !== CompanyDocumentStatusEnum::APROBADO) {
                    $allApproved = false;
                }
            }

            if ($allApproved && $this->canApproveAll()) {
                $this->record->update(['status' => 2]);

                Notification::make()
                    ->title('Empresa Aprobada')
                    ->success()
                    ->body('Todos los documentos han sido aprobados y la empresa ha sido validada.')
                    ->send();
            } else {
                $this->record->update(['status' => 3]);

                Notification::make()
                    ->title('Documentos Validados')
                    ->warning()
                    ->body('Los documentos han sido validados. La empresa requiere correcciones.')
                    ->send();
            }

            DB::commit();

            if (!$hasChanges) {
                Notification::make()
                    ->title('Sin cambios')
                    ->info()
                    ->body('No se han realizado cambios en la validación.')
                    ->send();
                return;
            }

            $this->redirect(static::getResource()::getUrl('index'));

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error')
                ->danger()
                ->body('Ocurrió un error al guardar la validación: ' . $e->getMessage())
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Volver')
                ->url(static::getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
