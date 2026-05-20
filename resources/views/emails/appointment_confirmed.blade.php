<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmación de Cita</title>
    <style>
        body { background:#0f172a; color:#e6e7ea; font-family:system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; margin:0; padding:0; }
        .container { max-width:640px; margin:32px auto; background:#0b1220; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(2,6,23,0.6); }
        .header { background:linear-gradient(90deg,#0b1220,#0f172a); padding:22px 28px; display:flex; align-items:center; justify-content:space-between; }
        .brand { font-weight:800; color:#f59e0b; letter-spacing:1px; font-size:18px; }
        .content { padding:28px; }
        h1 { margin:0 0 8px 0; font-size:20px; color:#fff; }
        p.lead { margin:0 0 16px 0; color:#cbd5e1; }
        .details { background:#071028; padding:18px; border-radius:8px; color:#e6e7ea; }
        .row { display:flex; justify-content:space-between; gap:12px; margin-bottom:8px; }
        .label { color:#9ca3af; font-size:12px; }
        .value { font-weight:700; color:#fff; }
        .cta { display:inline-block; margin-top:18px; padding:10px 14px; background:#f59e0b; color:#081127; border-radius:8px; font-weight:800; text-decoration:none; }
        .footer { padding:18px 28px; font-size:12px; color:#94a3b8; background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">BarbeShop</div>
            <div style="color:#94a3b8; font-size:12px;">Ticket: #{{ $appointment->id }}</div>
        </div>
        <div class="content">
            <h1>Tu cita ha sido confirmada</h1>
            <p class="lead">Gracias por reservar con nosotros. Adjuntamos el ticket de la cita en formato PDF para tu comprobante.</p>

            <div class="details">
                <div class="row"><div><div class="label">Cliente</div><div class="value">{{ $appointment->client->name }}</div></div><div><div class="label">Barbero</div><div class="value">{{ $appointment->barber->name ?? '—' }}</div></div></div>
                <div class="row"><div><div class="label">Servicio</div><div class="value">{{ $appointment->service->name ?? '—' }}</div></div><div><div class="label">Precio</div><div class="value">${{ number_format($appointment->service->price ?? 0, 2) }}</div></div></div>
                <div class="row"><div><div class="label">Fecha</div><div class="value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d/m/Y') }}</div></div><div><div class="label">Hora</div><div class="value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</div></div></div>
            </div>

            <a href="{{ url('/') }}" class="cta">Ver en el sistema</a>
        </div>
        <div class="footer">Si no solicitaste esta cita, contacta con soporte inmediatamente.</div>
    </div>
</body>
</html>
