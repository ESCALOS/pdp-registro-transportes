<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apelar Documentos Rechazados</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #ef4444;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .company-info {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            border-left: 4px solid #ef4444;
        }
        .company-info p {
            margin: 5px 0;
        }
        .instructions {
            background-color: #fef3c7;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 30px;
            border-left: 4px solid #f59e0b;
        }
        .instructions p {
            color: #92400e;
            line-height: 1.6;
        }
        .document-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .document-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .rejection-reason {
            background-color: #fee2e2;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            color: #991b1b;
        }
        .rejection-reason strong {
            display: block;
            margin-bottom: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #374151;
        }
        .form-input {
            width: 100%;
            padding: 10px;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .error {
            color: #dc2626;
            font-size: 14px;
            margin-top: 5px;
        }
        .submit-btn {
            background-color: #10b981;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #059669;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .file-info {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Apelar Documentos Rechazados</h1>
            <p>Por favor, vuelva a cargar los documentos observados</p>
        </div>

        <div class="content">
            <div class="company-info">
                <p><strong>RUC:</strong> {{ $company->ruc }}</p>
                <p><strong>Razón Social:</strong> {{ $company->business_name }}</p>
                <p><strong>Representante:</strong> {{ $company->representative->full_name }}</p>
            </div>

            <div class="instructions">
                <p><strong>Instrucciones:</strong></p>
                <p>• Revise cuidadosamente los motivos de rechazo de cada documento.</p>
                <p>• Cargue nuevamente los documentos corregidos.</p>
                <p>• Los archivos deben ser PDF, JPG, JPEG o PNG (máximo 5MB).</p>
                <p>• Una vez enviados, su solicitud volverá a estado pendiente para revisión.</p>
            </div>

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('company.appeal.update', $token) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @foreach($rejectedDocuments as $document)
                    <div class="document-card">
                        <div class="document-title">
                            {{ $document->type->getLabel() }}
                        </div>

                        @if($document->rejection_reason)
                            <div class="rejection-reason">
                                <strong>Motivo del rechazo:</strong>
                                {{ $document->rejection_reason }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="form-label" for="document_{{ $document->id }}">
                                Cargar nuevo documento *
                            </label>
                            <input
                                type="file"
                                id="document_{{ $document->id }}"
                                name="document_{{ $document->id }}"
                                class="form-input"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required
                            >
                            <p class="file-info">Formatos permitidos: PDF, JPG, JPEG, PNG (máximo 5MB)</p>
                            @error("document_{$document->id}")
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="submit-btn">
                    Enviar Documentos Corregidos
                </button>
            </form>
        </div>
    </div>
</body>
</html>
