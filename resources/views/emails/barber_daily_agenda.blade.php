<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agenda Diaria</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background: #f8fafc; color: #0f172a; padding: 20px; }
        .card { max-width: 700px; margin: 0 auto; background: #fff; border-radius: 8px; padding: 20px; border: 1px solid #e6edf3; }
        .brand { color: #f59e0b; font-weight: 800; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 13px; }
        th { background: #f8fafc; color: #475569; font-weight: 700; }
        .badge { background: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div class="brand">BarbeShop</div>
            <div style="color: #64748b; font-size: 14px;">Agenda para {{ $date }}</div>
        </div>

        <p>Hola <strong>{{ $barber->name }}</strong>,</p>
        <p>A continuación te presentamos un resumen de tus citas programadas para el día de hoy. También te dejamos el reporte completo en formato PDF adjunto a este correo.</p>

        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                    <tr>
                        <td><span class="badge">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</span></td>
                        <td>{{ $appt->client->name ?? '—' }}</td>
                        <td>{{ $appt->service->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>Por favor, asegúrate de revisar tu agenda adjunta para más detalles.</p>
        <p>Saludos,<br><span style="color: #f59e0b; font-weight: bold;">Equipo BarbeShop</span></p>
    </div>
</body>
</html>