<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Actualizar Stock - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Actualizar Stock</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <form action="{{ route('inventario.actualizar', $datosInventario->inventario_pk) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="cantidad_nueva" class="block text-sm font-medium text-gray-700">Cantidad de stock adicional</label>
                            <input type="number" id="cantidad_nueva" name="cantidad_nueva" required min="1" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="precio_proveedor" class="block text-sm font-medium text-gray-700">Precio del proveedor por unidad</label>
                            <input type="text" id="precio_proveedor" name="precio_proveedor" value="{{ $datosInventario->precio_proveedor }}" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    <div class="mt-6 text-right">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Actualizar Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->any())
    <script>
        Swal.fire({
            title: 'Errores de validación',
            html: '{!! implode(' < br > ', $errors->all()) !!}',
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