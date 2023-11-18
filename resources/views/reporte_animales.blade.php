<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Animales</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Reporte de Animales</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Especie</th>
                <th>Raza</th>
                <th>Edad</th>
                <th>Descripción</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($animales as $animal)
                <tr>
                    <td>{{ $animal->id }}</td>
                    <td>{{ $animal->nombre }}</td>
                    <td>{{ $animal->especie }}</td>
                    <td>{{ $animal->raza }}</td>
                    <td>{{ $animal->edad }}</td>
                    <td>{{ $animal->descripcion }}</td>
                    <td>{{ $animal->created_at }}</td>
                    <td>{{ $animal->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
