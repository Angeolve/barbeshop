<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Configuración de página y fuentes limpias */
        @page { margin: 0px; }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 13px; 
            color: #334155; 
            margin: 0px; 
            padding: 40px;
            background-color: #ffffff;
        }

        /* Encabezado estilo Barbería Premium */
        .header-container {
            background-color: #1e293b; /* Gris oscuro / Negro elegante */
            color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 6px solid #d97706; /* Detalle dorado/ámbar */
        }
        .brand-title {
            font-size: 24px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #f59e0b; /* Dorado */
            margin-bottom: 5px;
        }
        .agenda-subtitle {
            font-size: 14px;
            color: #94a3b8;
            font-weight: 400;
        }
        .meta-info {
            margin-top: 15px;
            font-size: 12px;
            color: #cbd5e1;
        }

        /* Tabla estilizada */
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            margin-top: 10px;
        }
        th { 
            background-color: #f8fafc; 
            color: #1e293b; 
            font-weight: 700; 
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid #e2e8f0;
        }
        td { 
            padding: 14px 16px; 
            border-bottom: 1px solid #f1f5f9; 
            color: #475569;
        }
        tr:nth-child(even) td {
            background-color: #f8fafc; /* Filas alternadas sutiles */
        }

        /* Componentes visuales dentro de la tabla */
        .time-badge {
            background-color: #fef3c7;
            color: #b45309;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }
        .client-name {
            font-weight: 600;
            color: #1e293b;
        }
        .service-name {
            color: #64748b;
            font-style: italic;
        }
        .price-value {
            font-weight: 700;
            color: #0f172a;
            text-align: right;
        }
        
        /* Footer del documento */
        .footer {
            position: absolute;
            bottom: 30px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
    <title>Agenda - {{ $barber->name }}</title>
</head>
<body>

    <div class="header-container">
        <div class="brand-title">BarbeShop</div>
        <div class="agenda-subtitle">Control de Agenda Diaria de Trabajo</div>
        <div class="meta-info">
            <strong>Barbero:</strong> {{ $barber->name }} <br>
           <strong>Fecha de Operación:</strong> {{ date('d/m/Y') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Hora</th>
                <th style="width: 35%;">Cliente</th>
                <th style="width: 30%;">Servicio Solicitado</th>
                <th style="width: 15%; text-align: right;">Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appt)
                <tr>
                    <td>
                        <span class="time-badge">
                            {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}
                        </span>
                    </td>
                    <td>
                        <span class="client-name">{{ $appt->client->name ?? 'Cliente General' }}</span>
                    </td>
                    <td>
                        <span class="service-name">{{ $appt->service->name ?? 'Servicio Estándar' }}</span>
                    </td>
                    <td class="price-value">
                        ${{ number_format($appt->service->price ?? 0, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado automáticamente por el sistema de gestión BarbeShop. Por favor, asista a sus citas a tiempo.
    </div>

</body>
</html>