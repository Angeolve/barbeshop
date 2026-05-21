<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recordatorio de Cita</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;background:#f8fafc;color:#0f172a;padding:20px}
        .card{max-width:600px;margin:0 auto;background:#fff;border-radius:8px;padding:20px;border:1px solid #e6edf3}
        .brand{color:#f59e0b;font-weight:800}
        .muted{color:#6b7280;font-size:13px}
        .cta{display:inline-block;padding:10px 14px;background:#f59e0b;color:#071127;border-radius:6px;font-weight:700;text-decoration:none}
    </style>
</head>
<body>
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <div class="brand">BarbeShop</div>
            <div class="muted">Recordatorio automático</div>
        </div>

        <h3>Hola {{ $appointment->client->name }},</h3>
        <p class="muted">Te recordamos que tienes una cita programada.</p>

        <p><strong>Servicio:</strong> {{ $appointment->service->name ?? '—' }}<br>
        <strong>Barbero:</strong> {{ $appointment->barber->name ?? '—' }}<br>
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d/m/Y') }}<br>
        <strong>Hora:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>

        <p>Si necesitas cambiarla, entra a tu panel o contáctanos.</p>
        <p><a href="{{ url('/') }}" class="cta">Ver mi cita</a></p>
    </div>
</body>
</html>
