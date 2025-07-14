<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Edición de Pedido - La Monalezza</title>
    {{-- Tailwind --}}
    @vite('resources/css/app.css')
</head>

<body class="h-full bg-gray-100 overflow-hidden">
    @include('sidebar')

    @php
        use Carbon\Carbon;
        $USUARIO = session('usuario');
    @endphp

    <div class="main-container">
        <div class="content-container">
            <!-- Columna izquierda -->
            <div class="left-column w-full md:w-1/2 lg:w-1/3 bg-white p-6 shadow-lg rounded-lg overflow-y-auto">
                <form action="{{ route('pedido.actualizar', $datosPedido->pedido_pk) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <h1 class="text-2xl font-bold mb-4">Editar Pedido</h1>

                    <div class="order-summary h-64">
                        <h2 class="text-lg font-medium mb-4">Resumen del Pedido</h2>
                        <div id="order-items" class="order-items overflow-y-auto h-[calc(100%-2rem)]">
                            <div id="productInputs" class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach ($datosPedido->detalle_pedido as $detalle)
                                    @php $producto = $detalle->producto; @endphp
                                    <div class="order-item flex items-center justify-between gap-2" id="order-item-{{ $producto->producto_pk }}">
                                        <span class="text-sm">{{ $producto->nombre_producto }} - ${{ $producto->precio_producto }}</span>
                                        <input type="number" name="productos[{{ $producto->producto_pk }}][cantidad_producto]" value="{{ $detalle->cantidad_producto }}" min="1" class="w-16 border rounded px-2 py-1" onchange="updateQuantity({{ $producto->producto_pk }}, this.value)">
                                        <button type="button" class="text-red-600 hover:underline" onclick="removeProductFromSummary({{ $producto->producto_pk }})">Eliminar</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="order-total flex justify-between items-center mt-4">
                            <span class="font-medium">Total:</span>
                            <span id="totalAmount" class="font-bold text-lg">$ {{ $datosPedido->monto_total }}</span>
                        </div>
                    </div>

                    <input type="hidden" name="monto_total" id="monto_total" value="{{ $datosPedido->monto_total }}">

                    <!-- Campos -->
                    <div class="space-y-4 overflow-y-auto flex-3 pb-8">
                        <div>
                            <label class="block font-medium mb-1">Cliente
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="cliente_fk" class="w-full border rounded-md px-3 py-2">
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->cliente_pk }}" {{ $datosPedido->cliente_fk == $cliente->cliente_pk ? 'selected' : '' }}>{{ $cliente->nombre_cliente }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Medio de Pedido
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="medio_pedido_fk" class="w-full border rounded-md px-3 py-2">
                                @foreach($mediosPedido as $medio)
                                    <option value="{{ $medio->medio_pedido_pk }}" {{ $datosPedido->medio_pedido_fk == $medio->medio_pedido_pk ? 'selected' : '' }}>{{ $medio->nombre_medio_pedido }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Tipo de Pago
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo_pago_fk" class="w-full border rounded-md px-3 py-2">
                                @foreach($tiposPago as $tipo)
                                    <option value="{{ $tipo->tipo_pago_pk }}" {{ $datosPedido->tipo_pago_fk == $tipo->tipo_pago_pk ? 'selected' : '' }}>{{ $tipo->nombre_tipo_pago }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Número de Transacción</label>
                            <input type="text" name="numero_transaccion" id="numero_transaccion" value="{{ $datosPedido->numero_transaccion }}" class="w-full border rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Notas de remisión</label>
                            <textarea name="notas_remision" class="w-full border rounded-md px-3 py-2">{{ $datosPedido->notas_remision }}</textarea>
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Pago
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="pago" id="pago" value="{{ $datosPedido->pago }}" class="w-full border rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Cambio</label>
                            <input type="number" name="cambio" id="cambio" value="{{ $datosPedido->cambio }}" readonly class="w-full border rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Fecha y Hora del Pedido
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="fecha_hora_pedido" value="{{ Carbon::parse($datosPedido->fecha_hora_pedido)->format('Y-m-d\TH:i') }}" class="w-full border rounded-md px-3 py-2">
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                                Actualizar Pedido
                            </button>
                        </div>

                        <p class="text-right text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario
                        </p>
                    </div>
                </form>
            </div>

            <!-- Columna derecha -->
            <div class="right-column w-full md:w-1/2 lg:w-3/5 p-6 overflow-y-auto">
                <input type="text" id="search-bar" placeholder="Buscar productos..." class="mb-4 p-2 w-full border rounded-md">
                <div id="product-container" class="menu-grid">
                    @foreach($productos as $producto)
                        <div class="menu-item relative bg-cover bg-center text-white p-4 rounded-lg shadow-md cursor-pointer"
                            style="background-image: url('{{ asset($producto->imagen_producto ?? 'img/sin-imagen.jpg') }}');"
                            data-nombre="{{ $producto->nombre_producto }}"
                            data-tipo="{{ $producto->tipo_producto->nombre_tipo_producto }}" 
                            data-precio="{{ $producto->precio_producto }}"
                            onclick="toggleProductSelection(this, {{ $producto->producto_pk }}, '{{ $producto->nombre_producto }}', {{ $producto->precio_producto }}, '{{ $producto->tipo_producto->nombre_tipo_producto }}')">
                            <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg"></div>
                            <div class="relative z-10 font-bold text-lg">{{ $producto->nombre_producto }}</div>
                            <div class="relative z-10 text-sm">{{ $producto->tipo_producto->nombre_tipo_producto }}</div>
                            <div class="relative z-10 font-semibold">${{ $producto->precio_producto }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleProductSelection(div, id, name, price) {
            const existingItem = document.getElementById(`order-item-${id}`);
            if (!existingItem) {
                const orderItem = document.createElement('div');
                orderItem.className = 'order-item flex items-center justify-between gap-2';
                orderItem.id = `order-item-${id}`;
                orderItem.innerHTML = `
                    <span class="text-sm">${name} - $${price}</span>
                    <input type="number" name="productos[${id}][cantidad_producto]" value="1" min="1" class="w-16 border rounded px-2 py-1" onchange="updateQuantity(${id}, this.value)">
                    <button type="button" class="text-red-600 hover:underline" onclick="removeProductFromSummary(${id})">Eliminar</button>
                `;
                document.getElementById('productInputs').appendChild(orderItem);
            } else {
                const input = existingItem.querySelector("input[type='number']");
                input.value = parseInt(input.value) + 1;
            }
            updateTotal();
        }

        function removeProductFromSummary(id) {
            const el = document.getElementById(`order-item-${id}`);
            if (el) el.remove();
            updateTotal();
        }

        function updateQuantity(id, val) {
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll(".order-item").forEach(item => {
                const input = item.querySelector("input[type='number']");
                const price = parseFloat(item.querySelector("span").textContent.split("$")[1]);
                total += price * parseInt(input.value);
            });
            document.getElementById('totalAmount').textContent = `$ ${total.toFixed(2)}`;
            document.getElementById('monto_total').value = total.toFixed(2);
            actualizarCambio();
        }

        const pagoInput = document.getElementById('pago');
        const cambioInput = document.getElementById('cambio');
        pagoInput.addEventListener('input', actualizarCambio);

        function actualizarCambio() {
            const total = parseFloat(document.getElementById('monto_total').value);
            const pago = parseFloat(pagoInput.value);
            const cambio = pago >= total ? pago - total : 0;
            cambioInput.value = cambio.toFixed(2);
        }

        document.getElementById('search-bar').addEventListener('input', function () {
            const value = this.value.toLowerCase();
            document.querySelectorAll('#product-container > div').forEach(item => {
                const nombre = item.dataset.nombre.toLowerCase();
                const precio = item.dataset.precio.toLowerCase();
                item.style.display = nombre.includes(value) || precio.includes(value) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
