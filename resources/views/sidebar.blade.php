<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Iconos --}}
    <link rel="stylesheet" href="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css') }}">
    <script src="https://kit.fontawesome.com/69e6d6a4a5.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- DataTables --}}
    <link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/datatables.min.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    @include('mensaje')
    
    @php
        $USUARIO_PK = session('usuario_pk');
        $USUARIO = session('usuario');
    @endphp

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
                <li class="mb-2"><a href="{{ route('inicio') }}" class="block p-2 hover:bg-gray-100 rounded">Inicio</a></li>
                <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Ventas</a></li>
                <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Inventario</a></li>
                <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Reportes</a></li>
                <li class="mb-2"><a href="{{ route('cliente.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Clientes</a></li>
                <li class="mb-2"><a href="{{ route('empleado.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Empleados</a></li>
                <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Realizar corte de caja</a></li>
                <li class="mb-2"><a href="#" class="block p-2 hover:bg-gray-100 rounded">Configuración del sistema</a></li>
                <li class="mb-2"><a href="{{ route('usuario.logout') }}" class="block p-2 hover:bg-gray-100 rounded">Cerrar sesión</a></li>
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
            <img src="{{ asset('img/logo_lamonalezza.webp') }}" class="w-16 h-16 bg-black rounded-full flex items-center justify-center text-white text-xs text-center">
        </div>
    </div>
    
</body>
</html>