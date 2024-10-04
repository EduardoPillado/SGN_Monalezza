<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Pizzería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="h-full bg-gray-100 overflow-hidden" x-data="{ sidebarOpen: false }">
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

        <!-- Contenido principal -->
        <div class="flex-grow flex overflow-hidden">
            <!-- Columna izquierda -->
            <div class="w-1/2 p-4 flex flex-col">
                <div class="bg-white shadow-md rounded-lg p-4 flex-grow flex flex-col">
                    <div class="mb-4 flex-grow overflow-y-auto">
                        <div class="flex justify-between items-center mb-2">
                            <span>Pizza #1 🍕</span>
                            <span>$170</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span>Pizza #2 🍕</span>
                            <span>$190</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span>Pizza #3 🍕</span>
                            <span>$200</span>
                        </div>
                    </div>
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center font-bold">
                            <span>Total:</span>
                            <span>$ 560.00</span>
                        </div>
                    </div>
                </div>
                <!-- Teclado numérico -->
                <div class="mt-4 grid grid-cols-4 gap-2">
                    <button class="bg-gray-200 p-2 rounded">1</button>
                    <button class="bg-gray-200 p-2 rounded">2</button>
                    <button class="bg-gray-200 p-2 rounded">3</button>
                    <button class="bg-gray-200 p-2 rounded">+/-</button>
                    <button class="bg-gray-200 p-2 rounded">4</button>
                    <button class="bg-gray-200 p-2 rounded">5</button>
                    <button class="bg-gray-200 p-2 rounded">6</button>
                    <button class="bg-gray-200 p-2 rounded">0</button>
                    <button class="bg-gray-200 p-2 rounded">7</button>
                    <button class="bg-gray-200 p-2 rounded">8</button>
                    <button class="bg-gray-200 p-2 rounded">9</button>
                    <button class="bg-gray-200 p-2 rounded">&lt;</button>
                    <button class="bg-blue-500 text-white p-2 rounded col-span-4">Cobrar</button>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="w-1/2 p-4 flex flex-col">
                <div class="bg-white shadow-md rounded-lg p-4 flex-grow flex flex-col">
                    <div class="grid grid-cols-2 gap-4 mb-4 flex-grow overflow-y-auto">
                        <div class="border p-2 text-center">
                            <div>Pizza #1</div>
                            <div>$170</div>
                            <div>🍕</div>
                        </div>
                        <div class="border p-2 text-center">
                            <div>Pizza #2</div>
                            <div>$190</div>
                            <div>🍕</div>
                        </div>
                        <div class="border p-2 text-center">
                            <div>Pizza #3</div>
                            <div>$200</div>
                            <div>🍕</div>
                        </div>
                        <div class="border p-2 text-center">
                            <div>Pizza #4</div>
                            <div>$250</div>
                            <div>🍕</div>
                        </div>
                        <div class="border p-2 text-center">
                            <div>Pizza #5</div>
                            <div>$250</div>
                            <div>🍕</div>
                        </div>
                    </div>
                    <!-- Botones inferiores -->
                    <div class="grid grid-cols-4 gap-2">
                        <div class="bg-red-500 text-white p-2 rounded text-center">
                            Productos registrados 🍕
                        </div>
                        <div class="bg-blue-500 text-white p-2 rounded text-center">
                            Total ventas 💰
                        </div>
                        <div class="bg-green-500 text-white p-2 rounded text-center">
                            Ganancias 💼
                        </div>
                        <div class="bg-red-500 text-white p-2 rounded text-center">
                            Productos poco Stock 📉
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>