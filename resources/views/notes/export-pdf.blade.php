<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de notas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="title">Reporte de notas</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Nota</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notes as $note)
                <tr>
                    <td>{{ $note->note_date?->locale('es')->translatedFormat('l d/m/Y') ?? '' }}</td>
                    <td>{{ $note->content ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
