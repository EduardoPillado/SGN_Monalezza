<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Edición de Producto - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    @php
        use App\Models\Tipo_producto;
        $datosTipoProducto=Tipo_producto::all();

        use App\Models\Ingrediente;
        $datosIngrediente=Ingrediente::where('estatus_ingrediente', '=', 1)->get();
    @endphp

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Edición de Producto</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <form action="{{ route('producto.actualizar', $datosProducto->producto_pk) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="nombre_producto" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" id="nombre_producto" name="nombre_producto" value="{{ $datosProducto->nombre_producto }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="tipo_producto_fk" class="block text-sm font-medium text-gray-700">Tipo de producto</label>
                            <select name="tipo_producto_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Selecciona el tipo de producto</option>
                                @foreach ($datosTipoProducto as $dato)
                                    <option @if ($dato->tipo_producto_pk == $datosProducto->tipo_producto_fk) selected @endif value="{{ $dato->tipo_producto_pk }}">{{ $dato->nombre_tipo_producto }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="precio_producto" class="block text-sm font-medium text-gray-700">Precio</label>
                            <input type="number" id="precio_producto" name="precio_producto" value="{{ $datosProducto->precio_producto }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen actual</label>
                        @if ($datosProducto->imagen_producto)
                            <div class="w-16 h-16 rounded overflow-hidden border border-gray-300">
                                <img src="{{ asset($datosProducto->imagen_producto) }}" alt="Imagen actual" class="w-full h-full object-cover">
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">No hay imagen registrada</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="imagen_producto" class="block text-sm font-medium text-gray-700">Reemplazar imagen</label>
                        <input type="file" id="imagen_producto" name="imagen_producto" accept="image/*" class="mt-1 block w-full text-sm text-gray-700">
                    </div>
                
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 items-center gap-1">
                            Ingredientes
                            <span class="text-blue-500 cursor-help" title="Solo agrega ingredientes si el producto los necesita.">?</span>
                        </label>
                        <div id="ingredientes-container">
                            @foreach ($datosProducto->ingredientes as $ingrediente)
                                <div class="flex items-center mb-2 ingrediente-row">
                                    <select name="ingredientes[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Selecciona un ingrediente</option>
                                        @foreach ($datosIngrediente as $dato)
                                            <option @if ($dato->ingrediente_pk == $ingrediente->ingrediente_pk) selected @endif value="{{ $dato->ingrediente_pk }}">{{ $dato->nombre_ingrediente }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="cantidades_necesarias[]" value="{{ $ingrediente->pivot->cantidad_necesaria }}" class="ml-2 w-20 rounded-md border-gray-300" required>
                                    <button type="button" class="ml-2 px-2 py-1 bg-red-500 text-white rounded-md remove-ingrediente">Eliminar</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-ingrediente" class="mt-2 px-3 py-1 bg-green-500 text-white rounded-md">Agregar Ingrediente</button>
                    </div>
                
                    <div class="mt-6 text-right">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-ingrediente').addEventListener('click', function() {
            let container = document.getElementById('ingredientes-container');
            let newRow = document.createElement('div');
            newRow.classList.add('flex', 'items-center', 'mb-2', 'ingrediente-row');
            
            newRow.innerHTML = `
                <select name="ingredientes[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Selecciona un ingrediente</option>
                    @foreach ($datosIngrediente as $dato)
                        <option value="{{ $dato->ingrediente_pk }}">{{ $dato->nombre_ingrediente }}</option>
                    @endforeach
                </select>

                <input type="number" name="cantidades_necesarias[]" class="ml-2 w-20 rounded-md border-gray-300" required>

                <button type="button" class="ml-2 px-2 py-1 bg-red-500 text-white rounded-md remove-ingrediente">Eliminar</button>
            `;
            
            container.appendChild(newRow);
        });
    
        // Eliminar un ingrediente específico del formulario
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-ingrediente')) {
                e.target.parentElement.remove();
            }
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