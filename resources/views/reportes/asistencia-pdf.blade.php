<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 18px;
        }

        .filters {
            margin-bottom: 12px;
        }

        .filters p {
            margin: 0 0 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px 8px;
            border: 1px solid #d1d5db;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>

    <div class="filters">
        <p><strong>Fecha inicio:</strong> {{ $filters['fecha_inicio'] }}</p>
        <p><strong>Fecha fin:</strong> {{ $filters['fecha_fin'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($row as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headings) }}">Sin resultados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
