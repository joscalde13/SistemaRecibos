<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo {{ $receipt->receipt_number }}</title>
    <style>
        @page { margin: 22px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 11px;
            margin: 0;
        }
        .page {
            width: 52%;
            min-height: 420px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        }
        .topbar {
            height: 7px;
            background: linear-gradient(90deg, #0f172a 0%, #374151 100%);
        }
        .wrap { padding: 18px 20px 16px 20px; }
        .header {
            display: table;
            width: 100%;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .left { display: table-cell; width: 62px; vertical-align: middle; }
        .right { display: table-cell; vertical-align: middle; }
        .logo-placeholder {
            width: 52px;
            height: 52px;
            border: 1px solid #d4af37;
            border-radius: 12px;
            text-align: center;
            line-height: 52px;
            font-weight: 700;
            background: linear-gradient(135deg, #f8f4ea 0%, #fdfaf1 100%);
            color: #7c5a1d;
        }
        .office-name {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.2px;
            color: #111827;
        }
        .meta { color: #4b5563; font-size: 10px; margin-top: 2px; }
        .title {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 10px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #374151;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }
        .amount-box {
            margin-top: 8px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 14px;
            color: #111827;
            font-size: 26px;
            font-weight: 700;
            text-align: center;
        }
        .amount-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 6px;
        }
        .grid { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grid td { padding: 4px 0; vertical-align: top; }
        .label { color: #475569; display: inline-block; width: 120px; font-size: 10px; }
        .box {
            margin-top: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px 10px;
            background: #fafafa;
        }
        .money {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 6px;
            margin-top: 12px;
        }
        .money td {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px 10px;
            width: 33.333%;
        }
        .k {
            color: #64748b;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
            margin-bottom: 2px;
        }
        .v {
            font-size: 13px;
            font-weight: 700;
        }
        .signature {
            margin-top: 26px;
            text-align: center;
        }
        .line {
            border-top: 1px solid #111827;
            width: 170px;
            margin: 8px auto 8px auto;
        }
        .note {
            margin-top: 10px;
            color: #6b7280;
            font-size: 9px;
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
        <div class="topbar"></div>
        <div class="wrap">
            <div class="header" style="display: block; border-bottom: none; padding-bottom: 6px; margin-bottom: 8px;">
                <div style="text-align: center; margin-bottom: 10px;">
                    @if($officeSetting?->logo_path && file_exists(public_path('storage/'.$officeSetting->logo_path)))
                        <img src="{{ public_path('storage/'.$officeSetting->logo_path) }}" alt="Logo" style="height: 64px; width: auto; max-width: 140px; object-fit: contain; border-radius: 10px; display: block; margin: 0 auto;">
                    @elseif($staticLogoPath)
                        <img src="{{ $staticLogoPath }}" alt="Logo" style="height: 64px; width: auto; max-width: 140px; object-fit: contain; border-radius: 10px; display: block; margin: 0 auto;">
                    @else
                        <div class="logo-placeholder" style="margin: 0 auto;">OJ</div>
                    @endif
                </div>
                <div style="text-align: center;">
                    <div class="office-name">{{ $officeSetting?->office_name ?: 'OFICINA JURIDICA ALVARO CALDERON S.' }}</div>
                    <div class="meta">{{ $officeSetting?->office_address ?: '12 calle 5-1 zona 1, Tiquisate, Escuintla' }}</div>
                    <div class="meta">{{ $officeSetting?->office_phone ?: '78855919' }}{{ $officeSetting?->office_email ? ' | '.$officeSetting->office_email : '' }}</div>
                    <div class="title">RECIBO DE PAGO</div>
                </div>
            </div>

            <div class="amount-label">Monto</div>
            <div class="amount-box">Q{{ number_format((float) $receipt->total_amount, 2) }}</div>

            <table class="grid">
                <tr>
                    <td><span class="label">Recibo:</span> <strong>{{ $receipt->receipt_number }}</strong></td>
                    <td style="text-align: right;"><span class="label">Fecha:</span> <strong>{{ $receipt->issue_date?->format('d/m/Y') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="label">Cliente:</span> <strong>{{ $receipt->person->full_name }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="label">Cantidad:</span> <strong>{{ $amountInWords }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="label">Concepto:</span> <strong>{{ $receipt->concept }}</strong></td>
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
                <div class="box">
                    <strong>Nota:</strong> {{ $receipt->notes }}
                </div>
            @endif

            <div class="signature">
                @if($officeSetting?->show_signature && $officeSetting?->signature_path && file_exists(public_path('storage/'.$officeSetting->signature_path)))
                    <img src="{{ public_path('storage/'.$officeSetting->signature_path) }}" alt="Firma" style="height: 58px; display: block; margin: 0 auto;">
                @endif
                <div class="line"></div>
                <div><strong>Firma</strong></div>
            </div>

            @if($officeSetting?->receipt_footer)
                <p class="note" style="margin-top: 16px;">{{ $officeSetting->receipt_footer }}</p>
            @endif
        </div>
    </div>
</body>
</html>
