<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Edición de Cliente - La Monalezza</title>
</head>

<body class="h-full bg-gray-100 overflow-hidden">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Edición de Cliente</h1>
            
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mt-2 px-7 py-3">
                            <form action="{{ route('cliente.actualizar', $datosCliente->cliente_pk) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="mb-4">
                                    <label for="nombre_cliente" class="block text-sm font-medium text-gray-700">Nombre</label>
                                    <input type="text" id="nombre_cliente" name="nombre_cliente" value="{{ $datosCliente->nombre_cliente }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="mb-4">
                                    <label for="calle" class="block text-sm font-medium text-gray-700">Calle del domicilio</label>
                                    <input type="text" id="calle" name="calle" value="{{ $datosCliente->domicilio->calle }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="mb-4">
                                    <label for="numero_externo" class="block text-sm font-medium text-gray-700">Número externo</label>
                                    <input type="number" id="numero_externo" name="numero_externo" value="{{ $datosCliente->domicilio->numero_externo }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="mb-4">
                                    <label for="numero_interno" class="block text-sm font-medium text-gray-700">Número interno</label>
                                    <input type="number" id="numero_interno" name="numero_interno" value="{{ $datosCliente->domicilio->numero_interno }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="mb-4">
                                    <label for="referencias" class="block text-sm font-medium text-gray-700">Referencias</label>
                                    <textarea id="referencias" name="referencias" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ $datosCliente->domicilio->referencias }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <input type="text" id="telefono" name="telefono" value="{{ $datosCliente->telefono->telefono }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="items-center px-4 py-3">
                                    <button type="submit" class="mt-3 px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        Actualizar
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
    </div>
</body>
</html>