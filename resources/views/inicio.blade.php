<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Sistema de Gestión de Pizzería</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>
<body class="pizza-body" x-data="{ sidebarOpen: false }">
    @include('sidebar')

    @php
        use Carbon\Carbon;

        $USUARIO_PK = session('usuario_pk');
        $USUARIO = session('usuario');
    @endphp

    <div class="main-container">
        <div class="content-container">
            <!-- Columna izquierda -->
            <div class="left-column bg-white shadow-lg rounded-lg p-6 w-full md:w-1/2 lg:w-1/3">
                <form action="{{ route('pedido.insertar') }}" method="POST" class="space-y-4 h-full flex flex-col">
                    @csrf

                    <div class="order-summary h-64">

                        <h3 class="text-lg font-medium mb-4">Resumen del Pedido</h3>
                        <div id="order-items" class="order-items overflow-y-auto h-[calc(100%-2rem)]">
                            <div id="productInputs" class="space-y-2">
                                <!-- Productos seleccionados aquí -->
                            </div>
                        </div>

                        <div class="order-total flex justify-between items-center mt-4">
                            <span class="font-medium">Total:</span>
                            <span id="totalAmount" class="font-bold text-lg">$ 0.00</span>
                        </div>
                    </div>

                    <div class="space-y-4 overflow-y-auto flex-3 pb-8">
                        <!-- Campos del pedido -->
                        <p class="text-right text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario
                        </p>

                        <div>
                            <label for="cliente_fk" class="block font-medium mb-2">Cliente</label>
                            <!-- <select name="cliente_fk" id="cliente_fk" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Cliente genérico o Selecciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->cliente_pk }}">{{ $cliente->nombre_cliente }}</option>
                                @endforeach
                            </select> -->
                                    @csrf
                                    <div class="search-container">
                                        <!-- Input de búsqueda -->
                                        <input 
                                            type="search" 
                                            class="search__input" 
                                            id="buscarCliente" 
                                            placeholder="Selecciona un cliente" 
                                            oninput="filtrarClientes()" 
                                            onfocus="mostrarClientes()"
                                            autocomplete="off"
                                            required 
                                        />
                                        
                                        <input type="hidden" name="cliente_fk" id="cliente_fk">
                                        
                                        <!-- Dropdown con lista de clientes -->
                                        <div id="clientesDropdown" class="clientes-dropdown hidden">
                                            <div class="cliente-item" data-cliente-id="" data-nombre="Cliente genérico">
                                                Cliente genérico
                                            </div>
                                            @foreach($clientes as $cliente)
                                                <div class="cliente-item" data-cliente-id="{{ $cliente->cliente_pk }}" data-nombre="{{ $cliente->nombre_cliente }}">
                                                    {{ $cliente->nombre_cliente }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                <!-- Mostrar cliente seleccionado -->
                                <!-- <div id="clienteSeleccionado" class="mt-4 p-3 bg-gray-50 rounded-md hidden">
                                    <div class="text-sm text-gray-600">Cliente seleccionado:</div>
                                    <div id="nombreClienteSeleccionado" class="font-medium"></div>
                                </div> -->
                        </div>

                        <div>
                            <label for="empleado" class="block font-medium mb-2">Empleado
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" value="{{ $USUARIO }}" readonly class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="medio_pedido" class="block font-medium mb-2">Medio de Pedido
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="medio_pedido_fk" id="medio_pedido" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Selecciona un medio de pedido</option>
                                @foreach($mediosPedido as $medio)
                                    <option value="{{ $medio->medio_pedido_pk }}">{{ $medio->nombre_medio_pedido }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tipo_pago_fk" class="block font-medium mb-2">Tipo de Pago
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo_pago_fk" id="tipo_pago_fk" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Selecciona un tipo de pago</option>
                                @foreach($tiposPago as $tipo)
                                    <option value="{{ $tipo->tipo_pago_pk }}">{{ $tipo->nombre_tipo_pago }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="numero_transaccion" class="block font-medium mb-2">Número de Transacción</label>
                            <input type="text" id="numero_transaccion" name="numero_transaccion" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="notas_remision" class="block font-medium mb-2">Notas de remisión</label>
                            <textarea name="notas_remision" id="notas_remision" cols="30" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="pago" class="block font-medium mb-2">Pago
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="pago" id="pago" value="{{ old('pago') }}" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="cambio" class="block font-medium mb-2">Cambio</label>
                            <input type="number" id="cambio" name="cambio" value="{{ old('cambio') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" readonly>
                        </div>

                        <div>
                            <label for="fecha_hora_pedido" class="block font-medium mb-2">Fecha y Hora del Pedido
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="fecha_hora_pedido" id="fecha_hora_pedido" value="{{ Carbon::now()->format('Y-m-d\TH:i') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <input type="hidden" name="monto_total" id="monto_total" value="0">

                        <div class="flex justify-self-start">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                                Registrar Pedido
                            </button>
                        </div>

                    </div>

                </form>
                                
            </div>
            
            <!-- Columna derecha -->
            <div class="right-column">
                <input type="text" id="search-bar" placeholder="Buscar productos..." style="margin-bottom: 20px; padding: 10px; width: 100%; box-sizing: border-box;">
                <div id="product-container" class="menu-grid">
                    @foreach($productos as $producto)
                        <div class="menu-item relative bg-cover bg-center text-white p-4 rounded-lg shadow-md cursor-pointer"
                            style="background-image: url('{{ asset($producto->imagen_producto ?? 'img/sin-imagen.jpg') }}');"
                            data-nombre="{{ $producto->nombre_producto }}" 
                            data-tipo="{{ $producto->tipo_producto->nombre_tipo_producto }}" 
                            data-precio="{{ $producto->precio_producto }}" 
                            onclick="toggleProductSelection(this, {{ $producto->producto_pk }}, '{{ $producto->nombre_producto }}', {{ $producto->precio_producto }}, '{{ $producto->tipo_producto->nombre_tipo_producto }}')">
                            <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg"></div>
                            <input type="checkbox" name="producto_fk[]" value="{{ $producto->producto_pk }}" class="relative z-10">
                            <div class="relative z-5 font-bold text-lg">{{ $producto->nombre_producto }}</div>
                            <div class="relative z-5 text-sm">{{ $producto->tipo_producto->nombre_tipo_producto }}</div>
                            <div class="relative z-5 font-semibold">${{ $producto->precio_producto }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="info-buttons">
                    <a href="{{ route('producto.mostrar') }}">
                        <div class="info-button products-registered less">
                            Productos registrados 🍕
                        </div>
                    </a>
                    <a href="{{ route('pedido.mostrar') }}">
                        <div class="info-button total-sales less">
                            Total ventas 💰
                        </div>
                    </a>
                    <a href="{{ route('reserva.filtrar', ['fecha' => now()->toDateString()]) }}">
                        <div class="info-button profits relative less">
                            Reservaciones 🕐
                            @if(isset($cantidadReservasHoy) && $cantidadReservasHoy > 0)
                                <span class="absolute top-0 left-0 bg-green-700 text-white text-xs font-bold rounded-full px-2 py-1">
                                    {{ $cantidadReservasHoy }}
                                </span>
                            @endif
                        </div>
                    </a>
                    <a href="{{ route('inventario.filtrar', ['estado' => 'riesgo']) }}">
                        <div class="info-button low-stock relative less">
                            Inventario poco Stock 📉
                            @if(isset($cantidadCritico) && $cantidadCritico > 0)
                                <span class="absolute top-0 left-0 bg-red-700 text-white text-xs font-bold rounded-full px-2 py-1">
                                    {{ $cantidadCritico }}
                                </span>
                            @endif
                        </div>
                    </a>
                </div>
            </div>

            <!-- Modal de registro de efectivo inicial -->
            <div 
            x-data="{
                modalOpen: false,
                init() {
                    const lastShown = localStorage.getItem('modalLastShown');
                    const today = new Date().toDateString();
                    
                    fetch('/verificarRegistro')
                        .then(response => {
                            if (!response.ok) throw new Error('Error en la respuesta');
                            return response.json();
                        })
                        .then(data => {
                            if ((!lastShown || lastShown !== data.hoy) || !data.registroHoy) {
                                this.modalOpen = true;
                                localStorage.setItem('modalLastShown', data.hoy);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // this.modalOpen = true;
                        });
                }
            }" x-show="modalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
            <div id="modal-overlay" style="position: fixed; width: 100%; height: 100%; z-index: 1000; background: transparent;">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Apertura de caja</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-600 mb-3">
                                <span class="text-red-500">*</span> Campo necesario</p>
                            <form id="form-efectivoInicial" action="{{ route('entradas_caja.efectivoInicial') }}" method="post">
                                @csrf
                                <div class="mb-4">
                                     <label for="monto_entrada_caja" class="block text-sm font-medium text-gray-700">Monto
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" min="0.01" id="monto_entrada_caja" name="monto_entrada_caja"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                </div>
                                <div class="items-center px-4 py-3">
                                    <button type="submit" class="mt-3 px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Buscador de clientes -->
           <script>
                let clientesData = [];
                let clienteSeleccionado = null;

                // Inicializar datos de clientes al cargar la página
                document.addEventListener('DOMContentLoaded', function() {
                    const clienteItems = document.querySelectorAll('.cliente-item');
                    clienteItems.forEach(item => {
                        const cliente = {
                            id: item.dataset.clienteId,
                            nombre: item.dataset.nombre
                        };
                        clientesData.push(cliente);
                        
                        // Agregar event listener para selección
                        item.addEventListener('click', function() {
                            seleccionarCliente(cliente.id, cliente.nombre);
                        });
                    });
                });

                function filtrarClientes() {
                    const input = document.getElementById('buscarCliente').value.toLowerCase();
                    const dropdown = document.getElementById('clientesDropdown');
                    const clienteItems = dropdown.querySelectorAll('.cliente-item');
                    
                    let hayResultados = false;
                    
                    clienteItems.forEach(item => {
                        const nombre = item.dataset.nombre.toLowerCase();
                        if (nombre.includes(input)) {
                            item.style.display = 'block';
                            hayResultados = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    // Mostrar dropdown si hay texto y resultados
                    if (input.length > 0 && hayResultados) {
                        dropdown.classList.remove('hidden');
                    } else if (input.length === 0) {
                        dropdown.classList.remove('hidden'); // Mostrar todos si no hay texto
                        clienteItems.forEach(item => {
                            item.style.display = 'block';
                        });
                    } else {
                        dropdown.classList.add('hidden');
                    }
                }

                function mostrarClientes() {
                    const dropdown = document.getElementById('clientesDropdown');
                    dropdown.classList.remove('hidden');
                }

                function seleccionarCliente(clienteId, nombreCliente) {
                    // Actualizar input visible
                    document.getElementById('buscarCliente').value = nombreCliente;
                    
                    // Actualizar input hidden
                    document.getElementById('cliente_fk').value = clienteId;
                    
                    // Ocultar dropdown
                    document.getElementById('clientesDropdown').classList.add('hidden');
                    
                    // Actualizar selección visual
                    document.querySelectorAll('.cliente-item').forEach(item => {
                        item.classList.remove('selected');
                    });
                    const itemSeleccionado = document.querySelector(`[data-cliente-id="${clienteId}"]`);
                    if (itemSeleccionado) {
                        itemSeleccionado.classList.add('selected');
                    }
                    
                    // Mostrar cliente seleccionado
                    const clienteSeleccionadoDiv = document.getElementById('clienteSeleccionado');
                    const nombreDiv = document.getElementById('nombreClienteSeleccionado');
                    
                    nombreDiv.textContent = nombreCliente;
                    clienteSeleccionadoDiv.classList.remove('hidden');
                    
                    clienteSeleccionado = { id: clienteId, nombre: nombreCliente };
                }

                // Ocultar dropdown al hacer clic fuera
                document.addEventListener('click', function(event) {
                    const searchContainer = document.querySelector('.search-container');
                    if (!searchContainer.contains(event.target)) {
                        document.getElementById('clientesDropdown').classList.add('hidden');
                    }
                });

                // Manejar tecla Escape para cerrar dropdown
                document.getElementById('buscarCliente').addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        document.getElementById('clientesDropdown').classList.add('hidden');
                    }
                });
            </script>
            <!-- Buscador de clientes -->

            <script>
                $(document).ready(function() {
                    $('#search-bar').on('keyup', function() {
                        let value = $(this).val().toLowerCase();
                        $('#product-container .menu-item').filter(function() {
                            $(this).toggle(
                                $(this).data('nombre').toLowerCase().includes(value) ||
                                $(this).data('tipo').toLowerCase().includes(value) ||
                                $(this).data('precio').toString().toLowerCase().includes(value)
                            );
                        });
                    });
                });
            </script>
            
            <script>
                const selectedProducts = {};

                function toggleProductSelection(div, productId, productName, productPrice, productType) {
                    if (!selectedProducts[productId]) {
                        // Si no está aún, se agrega por primera vez
                        selectedProducts[productId] = {
                            name: productName,
                            price: productPrice,
                            type: productType,
                            quantity: 1
                        };

                        // Crea el resumen visual del producto
                        const productElement = document.createElement('div');
                        productElement.className = 'order-item';
                        productElement.id = `order-item-${productId}`;
                        productElement.innerHTML = `
                            <span id="product-info-${productId}">${productName} (${productType}) - $${productPrice}</span>
                            <input type="number" value="1" min="1" onchange="updateQuantity(${productId}, this.value)">
                            <button type="button" onclick="removeProductFromSummary(${productId})">Eliminar</button>
                        `;
                        document.getElementById('productInputs').appendChild(productElement);

                        // Input oculto para enviar cantidad
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `productos[${productId}][cantidad_producto]`;
                        hiddenInput.id = `input-producto-${productId}`;
                        hiddenInput.value = 1;
                        document.querySelector('form').appendChild(hiddenInput);

                        // Obtener el estado del stock y actualizar
                        fetch(`/producto/${productId}/estado-stock`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    console.log(data.error);
                                    return;
                                }

                                let stockMessage = '';
                                if (data.estadoStock === 'En riesgo') {
                                    stockMessage = '<span style="color: red; font-weight: bold;"> (Stock en riesgo)</span>';
                                } else if (data.estadoStock === 'Disponible') {
                                    stockMessage = '<span style="color: green; font-weight: bold;"> (Stock disponible)</span>';
                                } else if (data.estadoStock === 'No aplica') {
                                    stockMessage = '';
                                }

                                // Actualizar el contenido del span con el mensaje de stock
                                const productInfo = document.getElementById(`product-info-${productId}`);
                                productInfo.innerHTML = `${productName} (${productType}) - $${productPrice}${stockMessage}`;
                            })
                            .catch(error => {
                                console.log('Error al obtener el estado del stock:', error);
                            });
                    } else {
                        // Si ya existe, incrementa la cantidad
                        selectedProducts[productId].quantity++;

                        // Actualiza input de cantidad
                        const input = document.querySelector(`#order-item-${productId} input[type='number']`);
                        const hiddenInput = document.getElementById(`input-producto-${productId}`);

                        if (input && hiddenInput) {
                            input.value = selectedProducts[productId].quantity;
                            hiddenInput.value = selectedProducts[productId].quantity;
                        }
                    }

                    updateTotal();
                }

                function addProductToSummary(productId, productName, productPrice, productType) {
                    if (!selectedProducts[productId]) {
                        selectedProducts[productId] = {
                            name: productName,
                            price: productPrice,
                            type: productType,
                            quantity: 1
                        };

                        const productElement = document.createElement('div');
                        productElement.className = 'order-item';
                        productElement.id = `order-item-${productId}`;
                        productElement.innerHTML = `
                            <span>${productName} (${productType}) - $${productPrice}</span>
                            <input type="number" value="1" min="1" onchange="updateQuantity(${productId}, this.value)">
                            <button onclick="removeProductFromSummary(${productId})">Eliminar</button>
                        `;
                        document.getElementById('productInputs').appendChild(productElement);

                        // Crear el input oculto para el producto
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `productos[${productId}][cantidad_producto]`;
                        hiddenInput.id = `input-producto-${productId}`;
                        hiddenInput.value = 1;
                        document.querySelector('form').appendChild(hiddenInput);
                    }
                }

                function removeProductFromSummary(productId) {
                    delete selectedProducts[productId];
                    const productElement = document.getElementById(`order-item-${productId}`);
                    if (productElement) {
                        productElement.remove();
                    }

                    const hiddenInput = document.getElementById(`input-producto-${productId}`);
                    if (hiddenInput) {
                        hiddenInput.remove();
                    }

                    updateTotal();
                }

                function updateQuantity(productId, quantity) {
                    selectedProducts[productId].quantity = parseInt(quantity);

                    const hiddenInput = document.getElementById(`input-producto-${productId}`);
                    if (hiddenInput) {
                        hiddenInput.value = quantity;
                    }

                    updateTotal();
                }

                function updateTotal() {
                    let total = 0;

                    for (const productId in selectedProducts) {
                        const product = selectedProducts[productId];
                        total += product.price * product.quantity;
                    }

                    document.getElementById('totalAmount').textContent = `$ ${total.toFixed(2)}`;
                    document.getElementById('monto_total').value = total.toFixed(2);
                }



                // Obtén los elementos del formulario
                const montoTotalInput = document.getElementById('monto_total');
                const pagoInput = document.getElementById('pago');
                const cambioInput = document.getElementById('cambio');
                const submitButton = document.querySelector('button[type="submit"]');
                // Crear elemento para mostrar la alerta
                if (!document.getElementById('pago-alert')) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'text-red-500 mt-1 font-semibold text-sm hidden';
                    alertDiv.id = 'pago-alert';
                    alertDiv.textContent = 'La cantidad pagada es menor al costo del pedido';
                    // Insertar después del input de pago
                    pagoInput.insertAdjacentElement('afterend', alertDiv);
                }

                // Función para actualizar el cambio
                function actualizarCambio() {
                    const montoTotal = parseFloat(montoTotalInput.value) || 0;
                    const pago = parseFloat(pagoInput.value) || 0;

                    // Si el pago es mayor o igual al monto total, calcula el cambio
                    const cambio = pago >= montoTotal ? pago - montoTotal : 0;

                    // Actualiza el campo de cambio
                    cambioInput.value = cambio.toFixed(2);  // Redondeamos a 2 decimales

                    // Validar si el pago es suficiente
                    const alertDiv = document.getElementById('pago-alert');
                    const isPagoInsufficient = pago < montoTotal;
                    
                    if (isPagoInsufficient) {
                        // Mostrar alerta y deshabilitar botón
                        alertDiv.classList.remove('hidden');
                        pagoInput.classList.add('border-red-500');
                        pagoInput.classList.remove('border-gray-300');
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                        submitButton.classList.remove('hover:bg-blue-600');
                    } else {
                        // Ocultar alerta y habilitar botón
                        alertDiv.classList.add('hidden');
                        pagoInput.classList.remove('border-red-500');
                        pagoInput.classList.add('border-gray-300');
                        submitButton.disabled = false;
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitButton.classList.add('hover:bg-blue-600');
                    }
                }

                // Añadir eventos para calcular el cambio en tiempo real
                pagoInput.addEventListener('input', actualizarCambio);
                pagoInput.addEventListener('change', actualizarCambio);
                // Validar al cargar la página (por si hay valores predefinidos)
                document.addEventListener('DOMContentLoaded', actualizarCambio);
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

            @if (Session::has('pedido_exitoso'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        text: '{{ Session::get('pedido_exitoso') }}',
                        confirmButtonText: 'Ver Ticket',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const win = window.open("{{ route('ticket.mostrar', ['pedido_pk' => Session::get('pedido_pk')]) }}", "_blank");
                        }
                    });
                </script>
            @endif

            @if (Session::has('falta_stock'))
                <script>
                    Swal.fire({
                        icon: 'warning',
                        text: '{{ Session::get('falta_stock') }}',
                        confirmButtonText: 'Ver Ticket',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const win = window.open("{{ route('ticket.mostrar', ['pedido_pk' => Session::get('pedido_pk')]) }}", "_blank");
                        }
                    });
                </script>
            @endif

            @if (Session::has('registro_error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        text: '{{ Session::get('registro_error') }}',
                        showConfirmButton: true,
                        confirmButtonText: 'Entendido',
                        allowOutsideClick: false,
                    });
                </script>
            @endif

        </div>
    </div>
</body>
</html>
