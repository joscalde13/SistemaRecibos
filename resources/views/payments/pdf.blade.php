<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Abono {{ $payment->payment_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        .header { border-bottom: 2px solid #1f2937; padding-bottom: 10px; margin-bottom: 16px; }
        .office-name { font-size: 18px; font-weight: 700; }
        .meta { color: #4b5563; font-size: 11px; margin-top: 2px; }
        .title { margin-top: 8px; font-size: 14px; font-weight: 700; }
        .box { border: 1px solid #d1d5db; padding: 10px; margin-top: 10px; }
        .line { border-top: 1px solid #111827; width: 220px; margin: 0 auto 8px auto; }
        .signature { margin-top: 36px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        @if($officeSetting?->logo_path)
            <img src="{{ public_path('storage/'.$officeSetting->logo_path) }}" alt="Logo" style="height: 56px;">
        @elseif(file_exists(public_path('assets/logo/logo.jpeg')))
            <img src="{{ public_path('assets/logo/logo.jpeg') }}" alt="Logo" style="height: 56px;">
        @endif
        <div class="office-name">{{ $officeSetting?->office_name ?: 'OFICINA JURIDICA ALVARO CALDERON S.' }}</div>
        <div class="meta">{{ $officeSetting?->office_address ?: '12 calle 5-1 zona 1, Tiquisate, Escuintla' }}</div>
        <div class="meta">{{ $officeSetting?->office_phone ?: '78855919' }}{{ $officeSetting?->office_email ? ' | '.$officeSetting->office_email : '' }}</div>
        <div class="title">COMPROBANTE DE ABONO</div>
    </div>

    <p><strong>Numero:</strong> {{ $payment->payment_number }}</p>
    <p><strong>Fecha:</strong> {{ $payment->payment_date?->format('d/m/Y') }}</p>
    <p><strong>Persona:</strong> {{ $payment->person->full_name }}</p>
    <p><strong>Recibo:</strong> {{ $payment->receipt->receipt_number }}</p>

    <div class="box">
        <p><strong>Monto abonado:</strong> Q{{ number_format((float) $payment->amount, 2) }}</p>
        <p><strong>Cantidad en letras:</strong> {{ $amountInWords }}</p>
        @if($payment->notes)
            <p><strong>Nota:</strong> {{ $payment->notes }}</p>
        @endif
    </div>

    <div class="signature">
        @if($officeSetting?->show_signature && $officeSetting?->signature_path)
            <img src="{{ public_path('storage/'.$officeSetting->signature_path) }}" alt="Firma" style="height: 60px; display: block; margin: 0 auto;">
        @endif
        <div class="line"></div>
        <div>Firma del Notario</div>
        <div><strong>{{ $officeSetting?->notary_name ?: 'Alvaro Calderon S.' }}</strong></div>
    </div>
</body>
</html>
