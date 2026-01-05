<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

@php
    use Carbon\Carbon;
    $data = Carbon::now()->format('Y-m-d');
@endphp

<h1>Aktyvių bilietų ataskaita – {{ $data }}</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Pavadinimas</th>
            <th>Prioritetas</th>
            <th>Kategorija</th>
            <th>Statusas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bilietai as $b)
            <tr>
                <td>{{ $b->bilieto_id }}</td>
                <td>{{ $b->pavadinimas }}</td>
                <td>{{ $b->prioritetas }}</td>
                <td>{{ $b->kategorija }}</td>
                <td>{{ $b->statusas }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
