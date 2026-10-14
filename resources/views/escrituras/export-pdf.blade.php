<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de escrituras</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="title">Reporte de escrituras</div>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Teléfono</th>
                <th>No. escritura</th>
                <th>Fecha creación</th>
                <th>Fecha registro</th>
                <th>Fecha entrega</th>
                <th>Estado</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($escrituras as $escritura)
                <tr>
                    <td>{{ $escritura->client_name ?? '' }}</td>
                    <td>{{ $escritura->telefono ?? '' }}</td>
                    <td>{{ $escritura->escritura_number ?? '' }}</td>
                    <td>{{ $escritura->entry_date?->format('d/m/Y') ?? '' }}</td>
                    <td>{{ $escritura->registry_received_date?->format('d/m/Y') ?? '' }}</td>
                    <td>{{ $escritura->delivery_date?->format('d/m/Y') ?? '' }}</td>
                    <td>{{ $escritura->status ? (\App\Models\Escritura::statuses()[$escritura->status] ?? $escritura->status) : '' }}</td>
                    <td>{{ $escritura->notes ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
