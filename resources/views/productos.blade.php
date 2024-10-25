<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Productos - La Monalezza</title>
    @vite('resources/css/app.css')
    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="productManagement()">
    @php
        use App\Models\Tipo_producto;
        $datosTipoProducto=Tipo_producto::all();

        use App\Models\Proveedor;
        $datosProveedor=Proveedor::all();

        $USUARIO_PK = session('usuario_pk');
        $USUARIO = session('usuario');
        $ROL_PK = session('rol_pk');
        $ROL = session('nombre_rol');
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Productos</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table id="tabla-productos" class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nombre</th>
                            <th class="text-left py-2">Tipo de producto</th>
                            <th class="text-left py-2">Precio</th>
                            <th class="text-left py-2">Proveedor del producto</th>
                            <th class="text-left py-2">Estatus</th>
                            @if ( $ROL == 'Administrador' )
                                <th class="text-right py-2">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $datosProducto as $dato )
                            <tr class="border-b">
                                <td class="py-2">{{ $dato->nombre_producto }}</td>
                                <td class="py-2">{{ $dato->tipo_producto->nombre_tipo_producto }}</td>
                                <td class="py-2">${{ $dato->precio_producto }}</td>
                                @if ( $dato->proveedor )
                                    <td class="py-2">{{ $dato->proveedor->nombre_proveedor }}</td>
                                @else
                                    <td class="py-2"><em>Sin proveedor</em></td>
                                @endif
                                @if ( $dato->estatus_producto == 1 )
                                    <td class="py-2">Activo</td>
                                @else
                                    <td class="py-2">Inactivo</td>
                                @endif
                                @if ( $ROL == 'Administrador' )
                                    <td class="text-right py-2">
                                        <a href="{{ route('producto.datosParaEdicion', $dato->producto_pk) }}" 
                                           class="bg-blue-500 text-white px-2 py-1 rounded mr-2">
                                            Editar
                                        </a>
                                        @if ($dato->estatus_producto)
                                            <a href="{{ route('producto.baja', $dato->producto_pk) }}" 
                                               @click.prevent="confirmarBaja($event, '{{ $dato->producto_pk }}')"
                                               class="bg-red-500 text-white px-2 py-1 rounded">
                                                Dar de baja
                                            </a>
                                        @else
                                            <a href="{{ route('producto.alta', $dato->producto_pk) }}"
                                               @click.prevent="confirmarAlta($event, '{{ $dato->producto_pk }}')"
                                               class="bg-green-500 text-white px-2 py-1 rounded">
                                                Dar de alta
                                            </a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button @click="openModal()" 
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                    Registrar nuevo producto
                </button>
            </div>
        </div>

        <!-- Modal de registro -->
        <div x-show="isModalOpen" 
             @click.away="closeModal()"
             @keydown.escape.window="closeModal()"
             class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            
            <div class="relative bg-white rounded-lg p-8 max-w-md w-full mx-4"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="transform scale-95 opacity-0"
                 x-transition:enter-end="transform scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="transform scale-100 opacity-100"
                 x-transition:leave-end="transform scale-95 opacity-0">
                
                <h2 class="text-2xl font-bold mb-6 text-center">Registrar Nuevo Producto</h2>
                
                <form id="form-producto" @submit.prevent="submitForm" action="{{ route('producto.insertar') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="nombre_producto" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre del Producto
                            </label>
                            <input type="text" 
                                   id="nombre_producto" 
                                   name="nombre_producto" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   required>
                        </div>

                        <div>
                            <label for="tipo_producto_fk" class="block text-sm font-medium text-gray-700 mb-1">
                                Tipo de Producto
                            </label>
                            <select name="tipo_producto_fk" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    required>
                                <option value="">Selecciona el tipo de producto</option>
                                @foreach ($datosTipoProducto as $dato)
                                    <option value="{{ $dato->tipo_producto_pk }}">
                                        {{ $dato->nombre_tipo_producto }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="precio_producto" class="block text-sm font-medium text-gray-700 mb-1">
                                Precio
                            </label>
                            <input type="number" 
                                   id="precio_producto" 
                                   name="precio_producto" 
                                   step="0.01" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   required>
                        </div>

                        <div>
                            <label for="proveedor_fk" class="block text-sm font-medium text-gray-700 mb-1">
                                Proveedor
                            </label>
                            <select name="proveedor_fk" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Selecciona el proveedor</option>
                                @foreach ($datosProveedor as $dato)
                                    <option value="{{ $dato->proveedor_pk }}">
                                        {{ $dato->nombre_proveedor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <button type="submit" 
                                class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Guardar Producto
                        </button>
                        <button type="button" 
                                @click="closeModal()" 
                                class="w-full py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function productManagement() {
            return {
                isModalOpen: false,
                
                openModal() {
                    this.isModalOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                
                closeModal() {
                    this.isModalOpen = false;
                    document.body.style.overflow = 'auto';
                    document.getElementById('form-producto').reset();
                },
                
                async submitForm(e) {
                    const form = e.target;
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        });

                        if (response.ok) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: 'Producto registrado correctamente',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                            this.closeModal();
                        } else {
                            throw new Error('Error al registrar el producto');
                        }
                    } catch (error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al registrar el producto',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                },

                async confirmarBaja(event, productoPk) {
                    const result = await Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas dar de baja a este producto?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, dar de baja',
                        cancelButtonText: 'Cancelar'
                    });

                    if (result.isConfirmed) {
                        window.location.href = `{{ url('producto/baja') }}/${productoPk}`;
                    }
                },

                async confirmarAlta(event, productoPk) {
                    const result = await Swal.fire({
                        title: '¿Seguro?',
                        text: '¿Deseas dar de alta a este producto?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, dar de alta',
                        cancelButtonText: 'Cancelar'
                    });

                    if (result.isConfirmed) {
                        window.location.href = `{{ url('producto/alta') }}/${productoPk}`;
                    }
                }
            }
        }

        // Inicialización de DataTables
        $(document).ready(function() {
            $('#tabla-productos').DataTable({
                "language": {
                    "search": "Buscar:",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "zeroRecords": "Sin productos registrados",
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
</body>
</html>