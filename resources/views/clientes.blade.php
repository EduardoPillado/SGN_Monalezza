<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Gestión de Clientes - La Monalezza</title>
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    modalOpen: false,
    editModalOpen: false,
    editingClient: null
}">

    <div class="h-screen flex flex-col">
        @include('sidebar')

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Clientes</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">ID</th>
                            <th class="text-left py-2">Nombre</th>
                            <th class="text-left py-2">Domicilio</th>
                            <th class="text-left py-2">Teléfono</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-2">1</td>
                            <td class="py-2">Cliente #1</td>
                            <td class="py-2">Calle 123</td>
                            <td class="py-2">555-1234</td>
                            <td class="text-right py-2">
                                <button @click="editingClient = {cliente_pk: 1, nom_cliente: 'Cliente #1', domicilio_fk: 'Calle 123', telefono_fk: '555-1234'}; editModalOpen = true" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded">Eliminar</button>
                            </td>
                        </tr>
                        <!-- Más filas de clientes aquí -->
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button @click="modalOpen = true" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo cliente</button>
            </div>
        </div>

        <!-- Modal de registro de cliente -->
        <div x-show="modalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nuevo Cliente</h3>
                    <div class="mt-2 px-7 py-3">
                        <form @submit.prevent="// Aquí iría la lógica de envío del formulario">
                            <div class="mb-4">
                                <label for="nom_cliente" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="nom_cliente" name="nom_cliente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="domicilio_fk" class="block text-sm font-medium text-gray-700">Domicilio</label>
                                <input type="text" id="domicilio_fk" name="domicilio_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="telefono_fk" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="tel" id="telefono_fk" name="telefono_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="mt-3 px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de edición de cliente -->
        <div x-show="editModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Editar Cliente</h3>
                    <div class="mt-2 px-7 py-3">
                        <form @submit.prevent="// Aquí iría la lógica de actualización del cliente">
                            <div class="mb-4">
                                <label for="edit_nom_cliente" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="edit_nom_cliente" name="nom_cliente" x-model="editingClient.nom_cliente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="edit_domicilio_fk" class="block text-sm font-medium text-gray-700">Domicilio</label>
                                <input type="text" id="edit_domicilio_fk" name="domicilio_fk" x-model="editingClient.domicilio_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="edit_telefono_fk" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="tel" id="edit_telefono_fk" name="telefono_fk" x-model="editingClient.telefono_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="items-center px-4 py-3">
                                <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="mt-3 px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>