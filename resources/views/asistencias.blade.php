<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Asistencias - La Monalezza</title>
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Asistencias</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-asistencias" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Empleado</th>
                            <th class="text-left py-2">Fecha de asistencia</th>
                            <th class="text-left py-2">Hora de entrada</th>
                            <th class="text-left py-2">Hora de salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosAsistencia as $dato )
                            <tr class="border-b">
                                <td class="py-2">{{ $dato->empleado->usuario->nombre }}</td>
                                <td class="py-2">{{ $dato->fecha_asistencia }}</td>
                                <td class="py-2">{{ $dato->hora_entrada }}</td>
                                @if ( $dato->hora_salida == null )
                                    <td class="py-2"><em>No registrada</em></td>
                                @else
                                    <td class="py-2">{{ $dato->hora_salida }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-asistencias').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin asistencias registradas",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    }
                });
            });
        </script>
    </div>
</body>
</html>