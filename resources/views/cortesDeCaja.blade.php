<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Cortes de Caja - La Monalezza</title>
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    modalOpen: false,
}">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Cortes de Caja</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-cortesDeCaja" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Fecha inicial</th>
                            <th class="text-left py-2">Fecha final</th>
                            <th class="text-left py-2">Cantidad de ventas</th>
                            <th class="text-left py-2">Ganancias totales</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosCorteCaja as $dato )
                            <tr class="border-b cursor-pointer" onclick="toggleDetails('detalle-{{ $dato->corte_caja_pk }}')" title="CLIC PARA VER DETALLES">
                                <td class="py-2">{{ $dato->fecha_corte_inicio }}</td>
                                <td class="py-2">{{ $dato->fecha_corte_fin }}</td>
                                <td class="py-2">{{ $dato->cantidad_ventas }}</td>
                                <td class="py-2">${{ $dato->ganancia_total }}</td>
                                <td class="text-right py-2">
                                    <button onclick="printRecord({{ $dato->corte_caja_pk }})" type="button" class="bg-blue-500 text-white px-4 py-2 rounded">
                                        <span>Imprimir corte</span>
                                    </button>
                                </td>
                            </tr>

                            <tr id="detalle-{{ $dato->corte_caja_pk }}" class="hidden">
                                <td colspan="10" class="p-4 bg-gray-50">
                                    <strong>Empleados que realizaron las ventas:</strong>
                                    <ul>
                                        @foreach ($dato->corte_empleado as $detalle)
                                            <li>
                                                {{ $detalle->empleado->usuario->usuario }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button @click="modalOpen = true" class="bg-green-500 text-white px-4 py-2 rounded">Realizar corte de caja</button>
            </div>
        </div>

        <script>
            $.fn.dataTable.ext.errMode = 'none';
            // Tabla con DataTable
            $(document).ready(function () {
                $('#tabla-cortesDeCaja').DataTable({
                    "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin cortes registrados",
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

            function toggleDetails(id) {
                const row = document.getElementById(id);
                if (row) {
                    row.classList.toggle('hidden');
                }
            }
        </script>

        <!-- Modal de corte de caja -->
        <div x-show="modalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Realizar Corte de Caja</h3>
                    <div class="mt-2 px-7 py-3">
                        <form id="form-corteCaja" action="{{ route('corteDeCaja.generarCorte') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="fecha_corte_inicio" class="block text-sm font-medium text-gray-700">Fecha inicial</label>
                                <input type="datetime-local" id="fecha_corte_inicio" name="fecha_corte_inicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="fecha_corte_fin" class="block text-sm font-medium text-gray-700">Fecha final</label>
                                <input type="datetime-local" id="fecha_corte_fin" name="fecha_corte_fin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="mt-3 px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                                    Generar Corte
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <script>
                Swal.fire({
                    title: 'Errores de validación',
                    html: '{!! implode('<br>', $errors->all()) !!}',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            </script>
        @endif
    </div>
    
    <script>
        function printRecord(recordId) {
            // Obtener la fila del registro específico usando el ID
            var recordRow = document.querySelector(`#tabla-cortesDeCaja tbody tr:nth-child(${recordId * 2 - 1})`);
            var detailsRow = document.getElementById(`detalle-${recordId}`);
            
            // Clonar la fila del registro y excluir la última columna (acciones)
            var clonedRecordRow = recordRow.cloneNode(true);
            clonedRecordRow.removeChild(clonedRecordRow.lastElementChild);
    
            // Clonar la fila de detalles, si existe
            var detailsContent = detailsRow ? detailsRow.cloneNode(true).outerHTML : '';
    
            // Crear la ventana de impresión
            var printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write('<html><head><title>Corte de Caja</title></head><body>');
    
            // Encabezado con logo
            printWindow.document.write('<div style="text-align: center; margin-bottom: 20px;">');
            printWindow.document.write('<img src="{{ asset("img/logo_lamonalezza.webp") }}" alt="Logo" style="width: 150px; max-width: 100%; height: auto;" />');
            printWindow.document.write('</div>');
            
            // Estilos personalizados para impresión
            printWindow.document.write('<style type="text/css" media="print"> \
                body { font-family: Arial, sans-serif; } \
                .print-section { width: 100%; margin: 0 auto; text-align: center; padding: 10px; } \
                table { border-collapse: collapse; width: 60%; margin: 0 auto; } \
                th, td { border: 1px solid #000; padding: 10px; text-align: center; } \
                th { background-color: #f2f2f2; font-weight: bold; } \
                .section-title { font-weight: bold; margin-top: 20px; text-align: left; width: 60%; margin: 0 auto; } \
                .header-text { display: flex; justify-content: space-between; font-weight: bold; margin: 20px auto; width: 60%; } \
                .no-print { display: none; } \
                @page { margin: 1cm; } \
            </style>');
    
            // Encabezado de la sección de impresión
            printWindow.document.write('<div class="print-section">');
            printWindow.document.write('<div class="header-text"><span>Fecha inicial</span><span>Fecha final</span></div>');
            
            // Agregar la fila de datos y los detalles (excluyendo la columna de acciones)
            printWindow.document.write('<table>');
            printWindow.document.write('<tr><th>Fecha inicial</th><th>Fecha final</th><th>Cantidad de ventas</th><th>Ganancias totales</th></tr>');
            printWindow.document.write(clonedRecordRow.outerHTML);
            if (detailsContent) {
                printWindow.document.write(detailsContent);
            }
            printWindow.document.write('</table>');
    
            printWindow.document.write('</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>    
</body>
</html>