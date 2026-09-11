<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo {{ $receipt->receipt_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        html, body {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1a1a1a;
            font-size: 11px;
        }

        /* Página = mitad del alto de la hoja carta, pero más ancho horizontalmente */
        .page {
            width: 92%;
            max-width: 1100px;
            height: 5.8in;
            box-sizing: border-box;
            padding: 0.28in 0.45in;
            position: relative;
            margin: 0 auto;
        }

        /* Línea guía de corte al final de la mitad de hoja */
        .cut-line {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            border-top: 1px dashed #c9c9c9;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 1px solid #1a1a1a;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header-logo { display: table-cell; width: 60px; vertical-align: middle; }
        .header-info { display: table-cell; vertical-align: middle; padding-left: 12px; }
        .header-doc { display: table-cell; vertical-align: middle; text-align: right; width: 160px; }

        .logo-placeholder {
            width: 48px;
            height: 48px;
            border: 1px solid #1a1a1a;
            text-align: center;
            line-height: 48px;
            font-weight: 700;
            font-size: 13px;
            color: #1a1a1a;
        }

        .office-name {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .meta {
            color: #555555;
            font-size: 9.5px;
            margin-top: 2px;
        }

        .title {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #1a1a1a;
        }
        .receipt-number {
            font-size: 13px;
            font-weight: 700;
            margin-top: 3px;
        }
        .receipt-date {
            font-size: 9.5px;
            color: #555555;
            margin-top: 2px;
        }

        .amount-row {
            display: table;
            width: 100%;
            margin: 16px 0 14px 0;
        }
        .amount-label {
            display: table-cell;
            font-size: 9px;
            color: #555555;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            vertical-align: bottom;
            padding-bottom: 4px;
        }
        .amount-value {
            display: table-cell;
            text-align: right;
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .fields {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        .fields td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 10.5px;
        }
        .fields .label {
            color: #555555;
            display: inline-block;
            width: 90px;
        }

        .money {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            border-top: 1px solid #1a1a1a;
            border-bottom: 1px solid #1a1a1a;
        }
        .money td {
            padding: 8px 4px;
            width: 33.333%;
        }
        .money td + td {
            border-left: 1px solid #dddddd;
        }
        .k {
            color: #555555;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
            margin-bottom: 3px;
        }
        .v {
            font-size: 14px;
            font-weight: 700;
        }

        .note {
            margin-top: 10px;
            font-size: 9.5px;
            color: #555555;
        }
        .note strong { color: #1a1a1a; }

        .footer-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            width: 100%;
            margin-top: 52px;
            min-height: 150px;
            position: relative;
        }
        .footer-left {
            flex: 1;
            align-self: flex-end;
            font-size: 9px;
            color: #888888;
            padding-right: 18px;
        }
        .footer-signature {
            width: 240px;
            text-align: center;
            align-self: flex-end;
            margin: 0 auto;
            padding-top: 32px;
            padding-bottom: 0;
        }
        .signature-img {
            height: 60px;
            display: block;
            margin: 0 auto 12px auto;
        }
        .line {
            border-top: 1px solid #1a1a1a;
            width: 190px;
            margin: 0 auto 10px auto;
        }
        .sig-label {
            font-size: 9px;
            color: #555555;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    @php
        $staticLogoPath = null;
        $logoCandidates = [
            'assets/logo/logo.jpeg',
            'assets/logo/logo.png',
            'assets/logo/logo.jpg',
            'assets/logo/logo.svg',
            'assets/logo/logo-oficina.png',
            'assets/logo/logo-oficina.jpg',
            'assets/logo/logo-oficina.jpeg',
            'assets/logo/logo-oficina.svg',
            'images/logo-oficina.png',
            'images/logo-oficina.jpg',
            'images/logo-oficina.jpeg',
            'images/logo-oficina.svg',
            'images/logo-recibo.svg',
        ];

        foreach ($logoCandidates as $candidate) {
            if (file_exists(public_path($candidate))) {
                $staticLogoPath = public_path($candidate);
                break;
            }
        }

        if (! $staticLogoPath && file_exists(public_path('assets/logo/logo.jpeg'))) {
            $staticLogoPath = public_path('assets/logo/logo.jpeg');
        }

        $debeAmount = (float) ($receipt->total_amount ?? 0);
        $saldoAmount = (float) ($receipt->saldo_amount ?? 0);
    @endphp

    <div class="page">
        <div class="header">
            <div class="header-logo">
                @if($officeSetting?->logo_path && file_exists(public_path('storage/'.$officeSetting->logo_path)))
                    <img src="{{ public_path('storage/'.$officeSetting->logo_path) }}" alt="Logo" style="height: 48px; width: auto; max-width: 60px; object-fit: contain;">
                @elseif($staticLogoPath)
                    <img src="{{ $staticLogoPath }}" alt="Logo" style="height: 48px; width: auto; max-width: 60px; object-fit: contain;">
                @else
                    <div class="logo-placeholder">OJ</div>
                @endif
            </div>
            <div class="header-info">
                <div class="office-name">OFICINA JURIDICA LIC. ALVARO CALDERON S.</div>
                <div class="meta">{{ $officeSetting?->office_address ?: '12 calle 5-1 zona 1, Tiquisate, Escuintla' }}</div>
                <div class="meta">{{ $officeSetting?->office_phone ?: '7884-7778' }}{{ $officeSetting?->office_email ? ' | '.$officeSetting->office_email : '' }}</div>
            </div>
            <div class="header-doc">
                <div class="title">Recibo de pago</div>
                <div class="receipt-number">{{ $receipt->receipt_number }}</div>
                <div class="receipt-date">{{ $receipt->issue_date?->format('d/m/Y') }}</div>
            </div>
        </div>

        <div class="amount-row">
            <div class="amount-label">Monto</div>
            <div class="amount-value">Q{{ number_format((float) $receipt->total_amount, 2) }}</div>
        </div>

        <table class="fields">
            <tr>
                <td colspan="2"><span class="label">Cliente:</span> <strong>{{ $receipt->person->full_name }}</strong></td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Cantidad:</span> {{ $amountInWords }}</td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Concepto:</span> {{ $receipt->concept }}</td>
            </tr>
        </table>

        <table class="money">
            <tr>
                <td><span class="k">Debe</span><span class="v">Q{{ number_format((float) $receipt->total_amount, 2) }}</span></td>
                <td><span class="k">Abono</span><span class="v">Q{{ number_format((float) $paidAmount, 2) }}</span></td>
                <td><span class="k">Saldo</span><span class="v">Q{{ number_format((float) $saldoAmount, 2) }}</span></td>
            </tr>
        </table>

        @if($receipt->notes)
            <div class="note"><strong>Nota:</strong> {{ $receipt->notes }}</div>
        @endif

        <div class="footer-row">
            <div class="footer-left">
                @if($officeSetting?->receipt_footer)
                    {{ $officeSetting->receipt_footer }}
                @endif
            </div>
            <div class="footer-signature">
                @if($officeSetting?->show_signature && $officeSetting?->signature_path && file_exists(public_path('storage/'.$officeSetting->signature_path)))
                    <img src="{{ public_path('storage/'.$officeSetting->signature_path) }}" alt="Firma" class="signature-img">
                @endif
                <div class="line"></div>
                <div class="sig-label">Firma</div>
            </div>
        </div>

        
    </div>
</body>
</html>