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
            <div class="left-column">
                <form action="{{ route('pedido.insertar') }}" method="POST">
                    @csrf
                    <div class="order-summary">
                        <h3>Resumen del Pedido</h3>
                        <div id="order-items" class="order-items">
                            <div id="productInputs">
                                <!-- Productos seleccionados aquí -->
                            </div>
                        </div>
                        <div class="order-total">
                            <span>Total:</span>
                            <span id="totalAmount">$ 0.00</span>
                        </div>
                    </div>

                    <!-- Campos del pedido -->
                    <label for="cliente_fk">Cliente:</label>
                    <select name="cliente_fk" id="cliente_fk" required>
                        <option value="">Selecciona un cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->cliente_pk }}">{{ $cliente->nombre_cliente }}</option>
                        @endforeach
                    </select>
                    
                    <label for="empleado">Empleado:</label>
                    <input type="text" value="{{ $USUARIO }}" readonly>
                    {{-- <input type="hidden" name="empleado_fk" id="empleado_fk" value="{{ $USUARIO_PK }}"> --}}
                    
                    <label for="medio_pedido">Medio de Pedido:</label>
                    <select name="medio_pedido_fk" id="medio_pedido" required>
                        <option value="">Selecciona un medio de pedido</option>
                        @foreach($mediosPedido as $medio)
                            <option value="{{ $medio->medio_pedido_pk }}">{{ $medio->nombre_medio_pedido }}</option>
                        @endforeach
                    </select>
                    
                    <label for="tipo_pago_fk">Tipo de Pago:</label>
                    <select name="tipo_pago_fk" id="tipo_pago_fk" required>
                        <option value="">Selecciona un tipo de pago</option>
                        @foreach($tiposPago as $tipo)
                            <option value="{{ $tipo->tipo_pago_pk }}">{{ $tipo->nombre_tipo_pago }}</option>
                        @endforeach
                    </select>

                    <label for="notas_remision">Notas de remisión:</label>
                    <textarea name="notas_remision" id="notas_remision" cols="30" rows="10"></textarea>

                    <label for="fecha_hora_pedido">Fecha y Hora del Pedido:</label>
                    <input type="datetime-local" name="fecha_hora_pedido" id="fecha_hora_pedido" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>

                    <input type="hidden" name="monto_total" id="monto_total" value="0">

                    <button type="submit">Registrar Pedido</button>
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
                    <div class="info-button profits">
                        Ganancias 💼
                    </div>
                    <div class="info-button low-stock">
                        Productos poco Stock 📉
                    </div>
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
            </script>

        </div>
    </div>
</body>
</html>
