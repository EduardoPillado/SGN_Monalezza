<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados - La Monalizza</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    modalOpen: false,
    editModalOpen: false,
    editingEmployee: null
}">
    <div class="h-screen flex flex-col">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">Menú</h2>
                    <button @click="sidebarOpen = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <ul>
                    <li class="mb-2"><a href="inicio.php" class="block p-2 hover:bg-gray-100 rounded">Inicio</a></li>
                    <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Ventas</a></li>
                    <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Inventario</a></li>
                    <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Reportes</a></li>
                    <li class="mb-2"><a href="clientes.php" class="block p-2 hover:bg-gray-100 rounded">Clientes</a></li>
                    <li class="mb-2"><a href="empleados.php" class="block p-2 hover:bg-gray-100 rounded">Empleados</a></li>
                    <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Realizar corte de caja</a></li>
                    <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Configuración del sistema</a></li>

                </ul>
            </div>
        </div>

        <!-- Overlay para cerrar el sidebar en pantallas pequeñas -->
        <div @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" x-show="sidebarOpen"></div>

        <!-- Encabezado con logo y fondo de iconos de pizza -->
        <div class="bg-gray-200 p-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <!-- Aquí irían los iconos de pizza como background -->
            </div>
            <div class="relative z-10 flex justify-between items-center">
                <button class="text-2xl" @click="sidebarOpen = !sidebarOpen">☰</button>
                <img src="img/logo_lamonalezza.webp" class="w-16 h-16 bg-black rounded-full flex items-center justify-center text-white text-xs text-center">
            </div>
        </div>

        <div class="flex-grow overflow-y-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Empleados</h1>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">ID</th>
                            <th class="text-left py-2">Usuario</th>
                            <th class="text-left py-2">Fecha Contratación</th>
                            <th class="text-left py-2">Estatus</th>
                            <th class="text-right py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-2">1</td>
                            <td class="py-2">usuario1</td>
                            <td class="py-2">2023-01-15</td>
                            <td class="py-2">Activo</td>
                            <td class="text-right py-2">
                                <button @click="editingEmployee = {id: 1, usuario_fk: 'usuario1', fecha_contratacion: '2023-01-15', estatus: 'activo'}; editModalOpen = true" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded">Eliminar</button>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2">2</td>
                            <td class="py-2">usuario2</td>
                            <td class="py-2">2023-06-20</td>
                            <td class="py-2">Inactivo</td>
                            <td class="text-right py-2">
                                <button @click="editingEmployee = {id: 2, usuario_fk: 'usuario2', fecha_contratacion: '2023-06-20', estatus: 'inactivo'}; editModalOpen = true" class="bg-blue-500 text-white px-2 py-1 rounded mr-2">Editar</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button @click="modalOpen = true" class="bg-green-500 text-white px-4 py-2 rounded">Registrar nuevo empleado</button>
            </div>
        </div>
        <!-- Modal de registro de empleado -->
        <div x-show="modalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nuevo Empleado</h3>
                    <div class="mt-2 px-7 py-3">
                        <form @submit.prevent="// Aquí iría la lógica de envío del formulario">
                            <div class="mb-4">
                                <label for="usuario_fk" class="block text-sm font-medium text-gray-700">Usuario</label>
                                <input type="text" id="usuario_fk" name="usuario_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="fecha_contratacion" class="block text-sm font-medium text-gray-700">Fecha de Contratación</label>
                                <input type="date" id="fecha_contratacion" name="fecha_contratacion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="estatus" class="block text-sm font-medium text-gray-700">Estatus</label>
                                <select id="estatus" name="estatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
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
        <!-- Modal de edición de empleado -->
        <div x-show="editModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Editar Empleado</h3>
                    <div class="mt-2 px-7 py-3">
                        <form @submit.prevent="// Aquí iría la lógica de actualización del empleado">
                            <div class="mb-4">
                                <label for="edit_usuario_fk" class="block text-sm font-medium text-gray-700">Usuario</label>
                                <input type="text" id="edit_usuario_fk" name="usuario_fk" x-model="editingEmployee.usuario_fk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="edit_fecha_contratacion" class="block text-sm font-medium text-gray-700">Fecha de Contratación</label>
                                <input type="date" id="edit_fecha_contratacion" name="fecha_contratacion" x-model="editingEmployee.fecha_contratacion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="mb-4">
                                <label for="edit_estatus" class="block text-sm font-medium text-gray-700">Estatus</label>
                                <select id="edit_estatus" name="estatus" x-model="editingEmployee.estatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
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