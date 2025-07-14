<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Cortes de Caja - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Cortes de Caja</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-corte-caja" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Folio</th>
                            <th class="text-left py-2">Fecha inicial</th>
                            <th class="text-left py-2">Fecha final</th>
                            <th class="text-left py-2">Apertura de caja</th>
                            <th class="text-left py-2">Cantidad de ventas</th>
                            <th class="text-left py-2">Ganancias totales</th>
                            <th class="text-left py-2">Gasto en servicios</th>
                            <th class="text-left py-2">Saldo final</th>
                            <th class="no-print text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datosCorteCaja as $dato)
                            <tr id="registro-{{ $dato->corte_caja_pk }}" class="border-b cursor-pointer" title="CLIC PARA VER DETALLES" data-empleados='@json($dato->empleados)'>
                                <td class="py-2">{{ $dato->corte_caja_pk}}</td>
                                <td class="py-2">{{ $dato->fecha_corte_inicio }}</td>
                                <td class="py-2">{{ $dato->fecha_corte_fin }}</td>
                                <td class="py-2">${{ $dato->suma_efectivo_inicial }}</td>
                                <td class="py-2">{{ $dato->cantidad_ventas }}</td>
                                <td class="py-2">${{ $dato->ganancia_total }}</td>
                                <td class="py-2">${{ $dato->suma_gasto_servicios }}</td>
                                <td class="py-2">${{ $dato->utilidad_neta }}</td>
                                <td class="no-print text-right py-2">
                                    <button onclick="printRecord('registro-{{ $dato->corte_caja_pk }}')" type="button" class="bg-blue-500 text-white px-4 py-2 rounded">
                                        <span>Imprimir corte</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button data-modal-open class="bg-green-500 text-white px-4 py-2 rounded">Realizar corte de caja</button>
            </div>
        </div>

        <script>
            // Tabla con DataTable
            $(document).ready(function () {
                const table = $('#tabla-corte-caja').DataTable({
                    paging: false,
                    language: {
                        search: "Buscar:",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "No hay registros disponibles",
                        infoFiltered: "(filtrado de _MAX_ registros totales)",
                        zeroRecords: "Sin registros de cortes de caja",
                        lengthMenu: "Mostrar _MENU_ registros por página",
                        // paginate: {
                        //     first: "Primero",
                        //     last: "Último",
                        //     next: "Siguiente",
                        //     previous: "Anterior"
                        // }
                    }
                });

                // Añadir el evento de clic para expandir detalles
                $('#tabla-corte-caja tbody').on('click', 'tr', function () {
                    const row = table.row(this);
                    const empleados = $(this).data('empleados'); // Acceder a los empleados desde el atributo data-empleados

                    if (row.child.isShown()) {
                        // Si está expandido, lo oculta
                        row.child.hide();
                        $(this).removeClass('shown');
                    } else {
                        // Si no está expandido, lo muestra
                        row.child(formatDetails(empleados)).show();
                        $(this).addClass('shown');
                    }
                });

                // Función para formatear el contenido de los detalles
                function formatDetails(empleados) {
                    let contenido = 'No hay empleados asociados.';
                    
                    if (empleados && empleados.length > 0) {
                        contenido = empleados
                            .map(empleado => empleado.usuario ? `${empleado.usuario.usuario}` : 'Usuario no disponible')
                            .join(', ');
                    }

                    return `<div class="p-4 bg-gray-50">
                                <strong>Empleados que realizaron ventas:</strong> ${contenido}
                            </div>`;
                }
            });
        </script>

        <!-- Modal de corte de caja -->
        <div data-modal style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Realizar Corte de Caja</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario</p>
                        <form id="form-corteCaja" action="{{ route('corteDeCaja.generarCorte') }}" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="fecha_corte_inicio" class="block text-sm font-medium text-gray-700">Fecha inicial
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="fecha_corte_inicio" name="fecha_corte_inicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="mb-4">
                                <label for="fecha_corte_fin" class="block text-sm font-medium text-gray-700">Fecha final
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="fecha_corte_fin" name="fecha_corte_fin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" data-modal-cancel class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const openModalBtn = document.querySelector('[data-modal-open]');
                const modal = document.querySelector('[data-modal]');
                const cancelBtn = document.querySelector('[data-modal-cancel]');

                // Función para abrir el modal
                function openModal() {
                    modal.style.display = 'block';
                }

                // Función para cerrar el modal
                function closeModal() {
                    modal.style.display = 'none';
                }

                // Event listeners
                openModalBtn.addEventListener('click', openModal);
                cancelBtn.addEventListener('click', closeModal);

                // Cerrar modal si se hace click fuera de él
                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
            });
        </script>

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
            const noPrintElements = document.querySelectorAll('.no-print');
            noPrintElements.forEach(el => el.style.display = 'none');

            var recordRow = document.getElementById(recordId); // Seleccionar por ID único
            if (!recordRow) {
                alert('No se pudo encontrar el registro para imprimir.');
                return;
            }

            // Obtener datos de empleados
            const empleados = JSON.parse(recordRow.dataset.empleados || '[]');

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
                @page { margin: 1cm; } \
            </style>');

            // Tabla principal
            printWindow.document.write('<div class="print-section">');
            printWindow.document.write('<table>');
            printWindow.document.write('<tr><th>Folio</th><th>Fecha inicial</th><th>Fecha final</th><th>Efectivo inicial</th><th>Cantidad de ventas</th><th>Ganancias totales</th><th>Inversión</th><th>Utilidad neta</th></tr>');
            printWindow.document.write(recordRow.outerHTML);
            printWindow.document.write('</table>');

            if (empleados.length > 0) {
                printWindow.document.write('<table style="width: 94.4%; margin: 0 auto; border-collapse: collapse;">');
                printWindow.document.write('<tr><th>Empleados que realizaron ventas:</th></tr>');
                empleados.forEach(empleado => {
                    const usuario = empleado.usuario ? empleado.usuario.usuario : 'Empleado no disponible';
                    printWindow.document.write(`<tr><td>${usuario}</td></tr>`);
                });
                printWindow.document.write('</table>');
            } else {
                printWindow.document.write('<p style="text-align: center;">No hay empleados asociados.</p>');
            }

            printWindow.document.write('</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.onload = () => {
                printWindow.print();
                noPrintElements.forEach(el => el.style.display = ''); // Restaurar visibilidad
                printWindow.close();
            };
        }
    </script>
</body>
</html>