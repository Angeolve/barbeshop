<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket Cita #{{ $appointment->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color:#111; }
        .ticket { width:320px; margin:0 auto; padding:18px; border:1px dashed #d1d5db; }
        .brand { text-align:center; color:#0f172a; font-weight:800; letter-spacing:1px; margin-bottom:8px; }
        .accent { color:#f59e0b; font-weight:800; }
        .row { display:flex; justify-content:space-between; margin:6px 0; }
        .label { color:#6b7280; font-size:12px; }
        .value { font-size:14px; font-weight:700; }
        .footer { text-align:center; margin-top:12px; font-size:11px; color:#6b7280; }
        hr { border:none; border-top:1px dashed #e5e7eb; margin:10px 0; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="brand">BarbeShop</div>
        <div style="text-align:center; margin-bottom:6px;"><span class="accent">TICKET DE CITA</span></div>
        <hr />
        <div class="row"><div class="label">Folio</div><div class="value">#{{ $appointment->id }}</div></div>
        <div class="row"><div class="label">Cliente</div><div class="value">{{ $appointment->client->name }}</div></div>
        <div class="row"><div class="label">Barbero</div><div class="value">{{ $appointment->barber->name ?? '—' }}</div></div>
        <div class="row"><div class="label">Servicio</div><div class="value">{{ $appointment->service->name ?? '—' }}</div></div>
        <div class="row"><div class="label">Fecha</div><div class="value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d/m/Y') }}</div></div>
        <div class="row"><div class="label">Hora</div><div class="value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</div></div>
        <hr />
        <div class="row"><div class="label">Precio</div><div class="value">${{ number_format($appointment->service->price ?? 0, 2) }}</div></div>
        <div class="footer">Gracias por preferirnos · BarbeShop</div>
    </div>
</body>
</html>
