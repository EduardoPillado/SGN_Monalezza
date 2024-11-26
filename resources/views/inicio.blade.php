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

                    <div class="space-y-4 overflow-y-auto flex-1 pb-8">
                        <!-- Campos del pedido -->
                        <div>
                            <label for="cliente_fk" class="block font-medium mb-2">Cliente:</label>
                            <select name="cliente_fk" id="cliente_fk" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Selecciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->cliente_pk }}">{{ $cliente->nombre_cliente }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="empleado" class="block font-medium mb-2">Empleado:</label>
                            <input type="text" value="{{ $USUARIO }}" readonly class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="medio_pedido" class="block font-medium mb-2">Medio de Pedido:</label>
                            <select name="medio_pedido_fk" id="medio_pedido" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Selecciona un medio de pedido</option>
                                @foreach($mediosPedido as $medio)
                                    <option value="{{ $medio->medio_pedido_pk }}">{{ $medio->nombre_medio_pedido }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tipo_pago_fk" class="block font-medium mb-2">Tipo de Pago:</label>
                            <select name="tipo_pago_fk" id="tipo_pago_fk" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Selecciona un tipo de pago</option>
                                @foreach($tiposPago as $tipo)
                                    <option value="{{ $tipo->tipo_pago_pk }}">{{ $tipo->nombre_tipo_pago }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="notas_remision" class="block font-medium mb-2">Notas de remisión:</label>
                            <textarea name="notas_remision" id="notas_remision" cols="30" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="pago" class="block font-medium mb-2">Pago:</label>
                            <input type="number" name="pago" id="pago" value="{{ old('pago') }}" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="cambio" class="block font-medium mb-2">Cambio</label>
                            <input type="number" id="cambio" name="cambio" value="{{ old('cambio') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" readonly>
                        </div>

                        <div>
                            <label for="fecha_hora_pedido" class="block font-medium mb-2">Fecha y Hora del Pedido:</label>
                            <input type="datetime-local" name="fecha_hora_pedido" id="fecha_hora_pedido" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
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
                <!-- Tu contenido para la columna derecha permanece igual -->
                <div class="menu-grid">
                    @foreach($productos as $producto)
                        <div class="menu-item" onclick="toggleProductSelection(this, {{ $producto->producto_pk }}, '{{ $producto->nombre_producto }}', {{ $producto->precio_producto }}, '{{ $producto->tipo_producto->nombre_tipo_producto }}')">
                            <input type="checkbox" name="producto_fk[]" value="{{ $producto->producto_pk }}">
                            <div>{{ $producto->nombre_producto }}</div>
                            <div>{{ $producto->tipo_producto->nombre_tipo_producto }}</div>
                            <div>${{ $producto->precio_producto }}</div>
                            <div>🍕</div>
                        </div>
                    @endforeach
                </div>
                <div class="info-buttons">
                    <a href="{{ route('producto.mostrar') }}">
                        <div class="info-button products-registered">
                            Productos registrados 🍕
                        </div>
                    </a>
                    <a href="{{ route('pedido.mostrar') }}">
                        <div class="info-button total-sales">
                            Total ventas 💰
                        </div>
                    </a>
                    <a href="{{ route('reserva.mostrar') }}">
                        <div class="info-button profits">
                            Reservaciones 🕐
                        </div>
                    </a>
                    <a href="{{ route('inventario.mostrarPocoStock') }}">
                        <div class="info-button low-stock relative">
                            Productos poco Stock 📉
                            @if(isset($cantidadCritico) && $cantidadCritico > 0)
                                <span class="absolute top-0 left-0 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1">
                                    {{ $cantidadCritico }}
                                </span>
                            @endif
                        </div>
                    </a>
                </div>
            </div>
            
            <script>
                const selectedProducts = {};

                function toggleProductSelection(div, productId, productName, productPrice, productType) {
                    const checkbox = div.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    div.classList.toggle('selected', checkbox.checked);

                    if (checkbox.checked) {
                        addProductToSummary(productId, productName, productPrice, productType);
                    } else {
                        removeProductFromSummary(productId);
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

                // Función para actualizar el cambio
                function actualizarCambio() {
                    const montoTotal = parseFloat(montoTotalInput.value) || 0;
                    const pago = parseFloat(pagoInput.value) || 0;

                    // Si el pago es mayor o igual al monto total, calcula el cambio
                    const cambio = pago >= montoTotal ? pago - montoTotal : 0;

                    // Actualiza el campo de cambio
                    cambioInput.value = cambio.toFixed(2);  // Redondeamos a 2 decimales
                }

                // Añadir eventos para calcular el cambio en tiempo real
                pagoInput.addEventListener('input', actualizarCambio);
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
                            window.location.href = "{{ route('ticket.mostrar', ['pedido_pk' => Session::get('pedido_pk')]) }}";
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
                            window.location.href = "{{ route('ticket.mostrar', ['pedido_pk' => Session::get('pedido_pk')]) }}";
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
