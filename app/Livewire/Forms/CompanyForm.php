<?php

namespace App\Livewire\Forms;

use App\Enums\{CompanyTypeEnum, CompanyDocumentTypeEnum};
use App\Models\{Company, User, CompanyDocument};
use Illuminate\Support\Facades\{DB, Hash, Log, Storage};
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CompanyForm extends Form
{
    public int $type = 0;

    public string $ruc = '';
    public string $business_name = '';

    // Representative
    public string $representative_dni = '';
    public string $representative_name = '';
    public string $representative_last_name = '';
    public string $representative_email = '';
    public string $representative_password = '';
    public string $representative_password_confirmation = '';

    // Documents (TemporaryUploadedFile)
    public $ruc_document = null;
    public $representative_dni_document = null;
    public $sunarp_document = null; // Solo para jurídica
    public $power_of_attorney_document = null; // Solo para jurídica

    public function validateStep(int $step)
    {
        $rules = [];
        switch ($step) {
            case 1: // Datos de la Empresa
                $rules = [
                    'ruc' => [
                        'required',
                        'string',
                        'size:11',
                        'regex:/^\d{11}$/',
                        'unique:companies,ruc',
                        function ($attribute, $value, $fail) {
                            if ($this->type === CompanyTypeEnum::NATURAL->value && !str_starts_with($value, '10')) {
                                $fail('El RUC debe iniciar con 10 para personas naturales.');
                            }
                            if ($this->type === CompanyTypeEnum::JURIDICA->value && !str_starts_with($value, '20')) {
                                $fail('El RUC debe iniciar con 20 para personas jurídicas.');
                            }
                        }
                    ],
                    'business_name' => 'required|string|max:255',
                ];
                break;

            case 2: // Datos del Representante
                $rules = [
                    'representative_dni' => 'required|string|size:8|regex:/^\d{8}$/|unique:users,dni',
                    'representative_name' => 'required|string|max:255',
                    'representative_last_name' => 'required|string|max:255',
                    'representative_email' => 'required|email|unique:users,email',
                    'representative_password' => 'required|string|min:6|confirmed',
                    'representative_password_confirmation' => 'required|string|min:6',
                ];
                break;

            case 3: // Documentos
                $rules = [
                    'ruc_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'representative_dni_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ];

                // Documentos adicionales para empresas jurídicas
                if ($this->type === CompanyTypeEnum::JURIDICA->value) {
                    $rules['sunarp_document'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    $rules['power_of_attorney_document'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
                }
                break;
        }
        if (!empty($rules)) {
            $this->validate($rules);
        }
    }

    protected function rules()
    {
        $rules = [
            'type' => 'required|in:1,2',
            'ruc' => [
                'required',
                'string',
                'size:11',
                'regex:/^\d{11}$/',
                'unique:companies,ruc',
                function ($attribute, $value, $fail) {
                    if ($this->type === CompanyTypeEnum::NATURAL->value && !str_starts_with($value, '10')) {
                        $fail('El RUC debe iniciar con 10 para personas naturales.');
                    }
                    if ($this->type === CompanyTypeEnum::JURIDICA->value && !str_starts_with($value, '20')) {
                        $fail('El RUC debe iniciar con 20 para personas jurídicas.');
                    }
                }
            ],
            'business_name' => 'required|string|max:255',
            'representative_dni' => 'required|string|size:8|regex:/^\d{8}$/|unique:users,dni',
            'representative_name' => 'required|string|max:255',
            'representative_last_name' => 'required|string|max:255',
            'representative_email' => 'required|email|unique:users,email',
            'representative_password' => 'required|string|min:6|confirmed',
            'representative_password_confirmation' => 'required|string|min:6',

            // Documents
            'ruc_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'representative_dni_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        // Add documents for juridica companies
        if ($this->type === CompanyTypeEnum::JURIDICA->value) {
            $rules['sunarp_document'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $rules['power_of_attorney_document'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.size' => 'El RUC debe tener exactamente 11 caracteres.',
            'ruc.regex' => 'El RUC debe contener solo números.',
            'ruc.unique' => 'Este RUC ya está registrado.',
            'business_name.required' => 'La razón social es obligatoria.',
            'representative_dni.required' => 'El DNI del representante es obligatorio.',
            'representative_name.required' => 'El nombre del representante es obligatorio.',
            'representative_last_name.required' => 'Los apellidos del representante son obligatorios.',
            'representative_email.required' => 'El correo electrónico es obligatorio.',
            'representative_email.email' => 'Debe ser un correo electrónico válido.',
            'representative_email.unique' => 'Este correo ya está registrado.',
            'representative_password.required' => 'La contraseña es obligatoria.',
            'representative_password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'representative_password.confirmed' => 'Las contraseñas no coinciden.',
            'representative_dni.required' => 'El DNI del representante es obligatorio.',
            'representative_dni.size' => 'El DNI debe tener exactamente 8 caracteres.',
            'representative_dni.regex' => 'El DNI debe contener solo números.',
            'representative_dni.unique' => 'Este DNI ya está registrado.',

            // Documents messages
            'ruc_document.required' => 'La Ficha RUC es obligatoria.',
            'ruc_document.file' => 'La Ficha RUC debe ser un archivo válido.',
            'ruc_document.mimes' => 'La Ficha RUC debe ser PDF, JPG, JPEG o PNG.',
            'ruc_document.max' => 'La Ficha RUC no debe superar los 5MB.',

            'representative_dni_document.required' => 'El DNI del representante es obligatorio.',
            'representative_dni_document.file' => 'El DNI del representante debe ser un archivo válido.',
            'representative_dni_document.mimes' => 'El DNI del representante debe ser PDF, JPG, JPEG o PNG.',
            'representative_dni_document.max' => 'El DNI del representante no debe superar los 5MB.',

            'sunarp_document.required' => 'La Ficha SUNARP es obligatoria para empresas jurídicas.',
            'sunarp_document.file' => 'La Ficha SUNARP debe ser un archivo válido.',
            'sunarp_document.mimes' => 'La Ficha SUNARP debe ser PDF, JPG, JPEG o PNG.',
            'sunarp_document.max' => 'La Ficha SUNARP no debe superar los 5MB.',

            'power_of_attorney_document.required' => 'La Vigencia de Poder es obligatoria para empresas jurídicas.',
            'power_of_attorney_document.file' => 'La Vigencia de Poder debe ser un archivo válido.',
            'power_of_attorney_document.mimes' => 'La Vigencia de Poder debe ser PDF, JPG, JPEG o PNG.',
            'power_of_attorney_document.max' => 'La Vigencia de Poder no debe superar los 5MB.',
        ];
    }

    public function save(): array
    {
        return DB::transaction(function () {
            // Create company
            $company = Company::create([
                'type' => $this->type,
                'ruc' => $this->ruc,
                'business_name' => $this->business_name,
                'status' => 1, // Pendiente
            ]);

            // Create representative user
            $user = User::create([
                'dni' => $this->representative_dni,
                'name' => $this->representative_name,
                'last_name' => $this->representative_last_name,
                'email' => $this->representative_email,
                'password' => Hash::make($this->representative_password),
                'company_id' => $company->id,
                'is_company_representative' => true,
            ]);

            // Upload and save documents
            $this->saveDocument($company, CompanyDocumentTypeEnum::RUC_RECORD, $this->ruc_document);
            $this->saveDocument($company, CompanyDocumentTypeEnum::REPRESENTATIVE_DNI, $this->representative_dni_document);

            // Additional documents for juridica companies
            if ($this->type === CompanyTypeEnum::JURIDICA->value) {
                $this->saveDocument($company, CompanyDocumentTypeEnum::FICHA_SUNARP, $this->sunarp_document);
                $this->saveDocument($company, CompanyDocumentTypeEnum::POWER_OF_ATTORNEY_VALIDITY, $this->power_of_attorney_document);
            }

            return [$company, $user];
        });
    }

    private function saveDocument(Company $company, CompanyDocumentTypeEnum $type, TemporaryUploadedFile $file)
    {
        $filename = $company->ruc . '_' . strtolower(str_replace(' ', '_', $type->getLabel())) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('company-documents/' . $company->ruc, $filename, 'public');

        CompanyDocument::create([
            'company_id' => $company->id,
            'type' => $type->value,
            'path' => $path,
            'status' => 1, // Pendiente
            'submitted_date' => now(),
        ]);
    }
}
