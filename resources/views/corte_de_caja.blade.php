<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Corte de caja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('sidebar')

    <div class="container mx-auto px-4 py-8">
        <form>
            <div class="space-y-12">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                    <h1 class="text-2xl font-bold mb-4">Realizar Corte de Caja</h1>
            
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="fecha-inicio" class="block text-sm font-medium leading-6 text-gray-900">Fecha Inicial</label>
                            <div class="mt-2">
                                <input type="datetime-local" name="fecha-inicio" id="fecha-inicio" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
            
                        <div class="sm:col-span-3">
                            <label for="fecha-fin" class="block text-sm font-medium leading-6 text-gray-900">Fecha Final</label>
                            <div class="mt-2">
                                <input type="datetime-local" name="fecha-fin" id="fecha-fin" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
            
                        <div class="sm:col-span-3">
                            <label for="cantidad-ventas" class="block text-sm font-medium leading-6 text-gray-900">Cantidad de Ventas</label>
                            <div class="mt-2">
                                <input type="number" name="cantidad-ventas" id="cantidad-ventas" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
            
                        <div class="sm:col-span-3">
                            <label for="empleado" class="block text-sm font-medium leading-6 text-gray-900">Empleado que realizó la venta</label>
                            <div class="mt-2">
                                <select id="empleado" name="empleado" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                    <option>Empleado 1</option>
                                    <option>Empleado 2</option>
                                    <option>Empleado 3</option>
                                </select>
                            </div>
                        </div>
            
                        <div class="col-span-full">
                            <label for="notas" class="block text-sm font-medium leading-6 text-gray-900">Notas adicionales</label>
                            <div class="mt-2">
                                <textarea id="notas" name="notas" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-gray-600">Escriba cualquier información adicional relevante para el corte de caja.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</button>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Imprimir Corte</button>
            </div>
        </form>
    </div>
</body>
</html>