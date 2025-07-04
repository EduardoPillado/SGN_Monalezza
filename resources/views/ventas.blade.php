<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Ventas - La Monalezza</title>
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    modalOpen: false,
}">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Ventas</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-pedidos" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Cliente</th>
                            <th class="text-left py-2">Empleado</th>
                            <th class="text-left py-2">Fecha y hora</th>
                            <th class="text-left py-2">Medio del pedido</th>
                            <th class="text-left py-2">Total</th>
                            <th class="text-left py-2">Tipo de pago</th>
                            <th class="text-left py-2">Número de transacción</th>
                            <th class="text-left py-2">Notas de remisión</th>
                            <th class="text-left py-2">Estatus</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosPedido as $dato )
                            <tr class="border-b cursor-pointer" title="CLIC PARA VER DETALLES" data-detalles='@json($dato->detalle_pedido)'>
                                <td class="py-2">{{ $dato->cliente->nombre_cliente }}</td>
                                <td class="py-2">{{ $dato->empleado->usuario->usuario }}</td>
                                <td class="py-2">{{ $dato->fecha_hora_pedido }}</td>
                                <td class="py-2">{{ $dato->medio_pedido->nombre_medio_pedido }}</td>
                                <td class="py-2">${{ $dato->monto_total }}</td>
                                <td class="py-2">{{ $dato->tipo_pago->nombre_tipo_pago }}</td>
                                @if ( $dato->numero_transaccion )
                                    <td class="py-2">{{ $dato->numero_transaccion }}</td>
                                @else
                                    <td class="py-2"><em>Sin número de transacción</em></td>
                                @endif
                                @if ( $dato->numero_transaccion )
                                    <td class="py-2">{{ $dato->notas_remision }}</td>
                                @else
                                    <td class="py-2"><em>Sin notas de remisión</em></td>
                                @endif
                                @if ( $dato->estatus_pedido == 1 )
                                    <td class="py-2">Pendiente</td>
                                @elseif ( $dato->estatus_pedido == 0 )
                                    <td class="py-2">Entregado</td>
                                @elseif ( $dato->estatus_pedido == 2 )
                                    <td class="py-2">Cancelado</td>
                                @else
                                    <td class="py-2"><em>Sin estatus aplicado</em></td>
                                @endif
                                <td class="text-right py-2">
                                    @if ( $dato->estatus_pedido == 1 )
                                        <a href="{{ route('pedido.entregado', $dato->pedido_pk) }}" onclick="confirmarEntrega(event)" class="bg-green-500 text-white px-2 py-1 rounded">Entregado</a>
                                    @else
                                        
                                    @endif

                                    @if ( $dato->estatus_pedido != 2 )
                                        <a href="{{ route('pedido.cancelado', $dato->pedido_pk) }}" onclick="confirmarCancelacion(event)" class="bg-red-500 text-white px-2 py-1 rounded">Cancelar</a>
                                    @elseif ( $dato->estatus_pedido == 2 )
                                        <a href="{{ route('pedido.pendiente', $dato->pedido_pk) }}" onclick="confirmarDesCancelacion(event)" class="bg-green-500 text-white px-2 py-1 rounded">Deshacer cancelación</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                const table = $('#tabla-pedidos').DataTable({
                    language: {
                        search: "Buscar:",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "No hay registros disponibles",
                        infoFiltered: "(filtrado de _MAX_ registros totales)",
                        zeroRecords: "Sin registros de pedidos",
                        lengthMenu: "Mostrar _MENU_ registros por página",
                        paginate: {
                            first: "Primero",
                            last: "Último",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    }
                });

                // Añadir el evento de clic para expandir detalles
                $('#tabla-pedidos tbody').on('click', 'tr', function () {
                    const row = table.row(this);
                    const detalles = $(this).data('detalles'); // Acceder a los detalles desde el atributo data-detalles

                    if (row.child.isShown()) {
                        // Si está expandido, lo oculta
                        row.child.hide();
                        $(this).removeClass('shown');
                    } else {
                        // Si no está expandido, lo muestra
                        row.child(formatDetails(detalles)).show();
                        $(this).addClass('shown');
                    }
                });

                // Función para formatear el contenido de los detalles
                function formatDetails(detalles) {
                    let contenido = 'No hay productos asociados.';
                    
                    if (detalles && detalles.length > 0) {
                        contenido = '<ul>' + detalles.map(detalle => {
                            const producto = detalle.producto ? detalle.producto.nombre_producto : 'Producto no disponible';
                            const tipoProducto = detalle.producto && detalle.producto.tipo_producto ? detalle.producto.tipo_producto.nombre_tipo_producto : 'Tipo no disponible';
                            const precio = detalle.producto ? detalle.producto.precio_producto : 0;
                            const subtotal = detalle.cantidad_producto * precio;
                            
                            return `<li>
                                ${producto} (${tipoProducto}) - ${detalle.cantidad_producto} unidades - Precio: $${precio}
                                ${detalle.cantidad_producto >= 2 ? ` - Subtotal: $${subtotal}` : ''}
                            </li>`;
                        }).join('') + '</ul>';
                    }

                    return `<div class="p-4 bg-gray-50">
                                <strong>Productos:</strong> ${contenido}
                            </div>`;
                }
            });

            // Alerta de confirmación de entrega
            function confirmarEntrega(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas marcar la venta como entregada?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, entregado',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = link.href;
                        }
                    });
                }
            }

            // Alerta de confirmación de cancelación
            function confirmarCancelacion(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas marcar la venta como cancelada?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, cancelar',
                        cancelButtonText: 'No cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = link.href;
                        }
                    });
                }
            }

            // Alerta de confirmación de des cancelación
            function confirmarDesCancelacion(event) {
                event.preventDefault();
    
                const link = event.target.closest('a');
    
                if (link) {
                    Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas deshacer la cancelación?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, deshacer',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = link.href;
                        }
                    });
                }
            }
        </script>
    </div>
</body>
</html>