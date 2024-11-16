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
    {{-- Alpine.js CDN --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- DataTables --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .sidebar-hidden {
            transform: translateX(-100%);
        }
        .sidebar-visible {
            transform: translateX(0);
        }
    </style>
</head>
<body>

    @include('mensaje')
    
    @php
        $USUARIO_PK = session('usuario_pk');
        $USUARIO = session('usuario');
        $ROL_PK = session('rol_pk');
        $ROL = session('nombre_rol');
    @endphp

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out sidebar-hidden">
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Menú</h2>
                <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <ul>
                <li class="mb-2"><a href="{{ url('/') }}" class="block p-2 hover:bg-gray-100 rounded">Inicio</a></li>
                <li class="mb-2"><a href="{{ route('pedido.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Ventas</a></li>
                <li class="mb-2"><a href="{{ route('reserva.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Reservaciones</a></li>
                <li class="mb-2"><a href="{{ route('inventario.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Inventario</a></li>
                <li class="mb-2"><a href="{{ route('producto.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Productos</a></li>
                <li class="mb-2"><a href="{{ route('ingrediente.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Ingredientes</a></li>
                <li class="mb-2"><a href="{{ route('asistencia.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Asistencias</a></li>
                <li class="mb-2"><a href="{{ route('nomina.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Nómina</a></li>
                <li class="mb-2"><a href="{{ route('cliente.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Clientes</a></li>
                <li class="mb-2"><a href="{{ route('empleado.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Empleados</a></li>
                <li class="mb-2"><a href="{{ route('proveedor.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Proveedores</a></li>
                <li class="mb-2"><a href="{{ route('corteDeCaja.mostrar') }}" class="block p-2 hover:bg-gray-100 rounded">Realizar corte de caja</a></li>
                <li class="mb-2"><a href="{{ route('asistencia.entrada') }}" class="block p-2 hover:bg-gray-100 rounded">Registrar entrada</a></li>
                <li class="mb-2"><a href="{{ route('asistencia.salida') }}" class="block p-2 hover:bg-gray-100 rounded">Registrar salida</a></li>
                <li class="mb-2"><a href="{{ route('usuario.logout') }}" class="block p-2 hover:bg-gray-100 rounded">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>

    <!-- Overlay para cerrar el sidebar en pantallas pequeñas -->
    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden hidden"></div>

    <!-- Encabezado con logo y fondo de iconos de pizza -->
    <div class="bg-gray-200 p-4 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <!-- Aquí irían los iconos de pizza como background -->
        </div>
        <div class="relative z-10 flex justify-between items-center">
            <button class="text-2xl" onclick="toggleSidebar()">☰</button>
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo_lamonalezza.webp') }}" class="w-16 h-16 bg-black rounded-full flex items-center justify-center text-white text-xs text-center">
            </a>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            sidebar.classList.toggle('sidebar-hidden');
            sidebar.classList.toggle('sidebar-visible');
            overlay.classList.toggle('hidden');
        }
    </script>
    
</body>
</html>