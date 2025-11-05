<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa Aprobada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #10b981;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .company-info {
            background-color: white;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Felicitaciones!</h1>
    </div>
    <div class="content">
        <p>Estimado/a <strong>{{ $company->representative->full_name }}</strong>,</p>

        <p>Nos complace informarle que su empresa ha sido <strong>aprobada</strong> exitosamente.</p>

        <div class="company-info">
            <strong>Datos de la empresa:</strong><br>
            <strong>RUC:</strong> {{ $company->ruc }}<br>
            <strong>Razón Social:</strong> {{ $company->business_name }}<br>
            <strong>Tipo:</strong> {{ $company->type === 2 ? 'Persona Jurídica' : 'Persona Natural' }}
        </div>

        <p>Todos los documentos han sido validados correctamente y su empresa está ahora activa en nuestro sistema.</p>

        <p>Puede proceder a utilizar nuestros servicios.</p>

        <p>Saludos cordiales,<br>
        <strong>Equipo de Registro de Transportes</strong></p>
        
        <div style="text-align: center; margin-top: 30px;">
            <img src="https://i.ibb.co/mFNch0Qs/1-6d8e6b4a-6e62-4745-ab7b-dbf18aa12855.jpg" alt="Firma PDP Paracas" style="max-width: 100%; height: auto;">
        </div>
    </div>
    <div class="footer">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
