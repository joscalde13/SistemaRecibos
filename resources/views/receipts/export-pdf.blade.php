<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de recibos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="title">Reporte de recibos</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Concepto</th>
                <th>Monto</th>
                <th>Debe</th>
                <th>Abono</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receipts as $receipt)
                @php
                    $paid = (float) ($receipt->abono_amount ?? 0);
                    $debe = (float) ($receipt->total_amount ?? 0);
                    $saldo = (float) ($receipt->saldo_amount ?? 0);
                @endphp
                <tr>
                    <td>{{ $receipt->issue_date?->format('d/m/Y') }}</td>
                    <td>{{ $receipt->person?->full_name }}</td>
                    <td>{{ $receipt->concept ?? '' }}</td>
                    <td>Q{{ number_format((float) $receipt->total_amount, 2) }}</td>
                    <td>Q{{ number_format($debe, 2) }}</td>
                    <td>Q{{ number_format($paid, 2) }}</td>
                    <td>Q{{ number_format($saldo, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
