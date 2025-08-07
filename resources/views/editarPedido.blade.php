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
<body class="pizza-body" x-data="{ sidebarOpen: false }">
    @include('sidebar')

    @php
        use Carbon\Carbon;
        $USUARIO = session('usuario');
    @endphp

    <div class="main-container">
        <div class="content-container">
            <!-- Columna izquierda -->
            <div class="left-column bg-white shadow-lg rounded-lg p-6 w-full md:w-1/2 lg:w-1/3">
                <form action="{{ route('pedido.actualizar', $datosPedido->pedido_pk) }}" method="POST" class="space-y-4 h-full flex flex-col">
                    @csrf
                    @method('PUT')

                    <h1 class="text-2xl font-bold mb-4">Editar Pedido #{{ $datosPedido->pedido_pk }}</h1>

                    <div class="order-summary h-64">
                        <h3 class="text-lg font-medium mb-4">Resumen del Pedido</h3>
                        <div id="order-items" class="order-items overflow-y-auto h-[calc(100%-2rem)]">
                            <div id="productInputs" class="space-y-2">
                                {{-- Bucle para mostrar los productos existentes en el pedido --}}
                                @foreach ($datosPedido->detalle_pedido as $detalle)
                                    @php
                                        $producto = $detalle->producto; 
                                    @endphp
                                    <div class="order-item p-2 border-b" id="order-item-{{ $producto->producto_pk }}">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-grow text-sm">
                                                <span id="product-info-{{ $producto->producto_pk }}">
                                                    <strong>{{ $producto->nombre_producto }}</strong>
                                                </span>
                                                <br>
                                                <span>{{ $producto->tipo_producto->nombre_tipo_producto }} - ${{ number_format($producto->precio_producto, 2) }}</span>
                                                
                                                {{-- Ingredientes de Pizza Personalizada --}}
                                                @if ($producto->personalizable)
                                                    <div class="mt-1 text-xs text-gray-600" id="ingredientes-display-{{ $producto->producto_pk }}">
                                                        <span>
                                                            @forelse ($detalle->ingredientesPersonalizados as $ingredienteDetalle)
                                                                {{ $ingredienteDetalle->ingrediente->nombre_ingrediente }} ({{$ingredienteDetalle->cantidad_usada}} gr/ml)@if(!$loop->last) | @endif
                                                            @empty
                                                                Sin ingredientes de pizza personalizada.
                                                            @endforelse
                                                        </span>
                                                    </div>
                                                    <button type="button" class="edit-btn text-blue-600 hover:underline text-xs mt-1" onclick="abrirModalParaEditar({{ $producto->producto_pk }})">Editar Ingredientes</button>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Inputs ocultos para enviar los datos al backend --}}
                                        <div id="hidden-inputs-{{ $producto->producto_pk }}">
                                            <input type="hidden" name="productos[{{ $producto->producto_pk }}][cantidad_producto]" id="input-cantidad-{{ $producto->producto_pk }}" value="{{ $detalle->cantidad_producto }}">
                                            <input type="hidden" name="productos[{{ $producto->producto_pk }}][nombre_producto]" value="{{ $producto->nombre_producto }}">
                                            @if ($producto->personalizable)
                                                @foreach ($detalle->ingredientesPersonalizados as $ingredienteDetalle)
                                                    <input type="hidden" class="ingrediente-personalizado-{{ $producto->producto_pk }}" name="productos[{{ $producto->producto_pk }}][ingredientes_personalizados][{{ $ingredienteDetalle->ingrediente_fk }}]" value="{{ $ingredienteDetalle->cantidad_usada }}">
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="flex-shrink-0 w-48 flex items-center justify-between">
                                            <input type="number" value="{{ $detalle->cantidad_producto }}" min="1" class="w-16 border rounded px-2 py-1 text-center" onchange="updateQuantity({{ $producto->producto_pk }}, this.value)">
                                            <button type="button" class="text-red-600 hover:underline text-sm" onclick="removeProductFromSummary({{ $producto->producto_pk }})">Eliminar</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="order-total flex justify-between items-center mt-4">
                            <span class="font-medium">Total:</span>
                            <span id="totalAmount" class="font-bold text-lg">${{ number_format($datosPedido->monto_total, 2) }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 overflow-y-auto flex-3 pb-8">
                        <!-- Campos del pedido -->
                        <p class="text-right text-sm text-gray-600 mb-3">
                            <span class="text-red-500">*</span> Campo necesario
                        </p>

                        <div>
                            <label for="cliente_fk" class="block font-medium mb-2">Cliente</label>
                            <select name="cliente_fk" id="cliente_fk" style="width: 100%;">
                                <option value="">Cliente genérico</option> 
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->cliente_pk }}" {{ $datosPedido->cliente_fk == $cliente->cliente_pk ? 'selected' : '' }}>
                                        {{ $cliente->nombre_cliente }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <script>
                            $(document).ready(function() {
                                $('#cliente_fk').select2({
                                    placeholder: "Cliente genérico",
                                    allowClear: true
                                });
                            });
                        </script>

                        <div>
                            <label for="empleado" class="block font-medium mb-2">Empleado
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" value="{{ $USUARIO }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" readonly>
                        </div>

                        <div>
                            <label for="medio_pedido" class="block font-medium mb-2">Medio de Pedido
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="medio_pedido_fk" id="medio_pedido_fk" class="w-full border rounded-md px-3 py-2">
                                @foreach($mediosPedido as $medio)
                                    <option value="{{ $medio->medio_pedido_pk }}" {{ $datosPedido->medio_pedido_fk == $medio->medio_pedido_pk ? 'selected' : '' }}>{{ $medio->nombre_medio_pedido }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tipo_pago_fk" class="block font-medium mb-2">Tipo de Pago
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo_pago_fk" id="tipo_pago_fk" class="w-full border rounded-md px-3 py-2">
                                @foreach($tiposPago as $tipo)
                                    <option value="{{ $tipo->tipo_pago_pk }}" {{ $datosPedido->tipo_pago_fk == $tipo->tipo_pago_pk ? 'selected' : '' }}>{{ $tipo->nombre_tipo_pago }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="numero_transaccion" class="block font-medium mb-2">Número de Transacción</label>
                            <input type="text" id="numero_transaccion" name="numero_transaccion" value="{{ $datosPedido->numero_transaccion }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="notas_remision" class="block font-medium mb-2">Notas de remisión</label>
                            <textarea name="notas_remision" id="notas_remision" cols="30" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                {{ $datosPedido->notas_remision }}
                            </textarea>
                        </div>

                        <div>
                            <label for="pago" class="block font-medium mb-2">Pago
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="pago" id="pago" value="{{ $datosPedido->pago }}" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="cambio" class="block font-medium mb-2">Cambio</label>
                            <input type="number" id="cambio" name="cambio" value="{{ $datosPedido->cambio }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" readonly>
                        </div>

                        <div>
                            <label for="fecha_hora_pedido" class="block font-medium mb-2">Fecha y Hora del Pedido
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="fecha_hora_pedido" id="fecha_hora_pedido" value="{{ Carbon::parse($datosPedido->fecha_hora_pedido)->format('Y-m-d\TH:i') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <input type="hidden" name="monto_total" id="monto_total" value="{{ $datosPedido->monto_total }}">

                        <div class="flex justify-self-start">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                                Actualizar Pedido
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
                            data-personalizable="{{ $producto->personalizable }}" 
                            onclick="toggleProductSelection(this, {{ $producto->producto_pk }}, '{{ $producto->nombre_producto }}', {{ $producto->precio_producto }}, '{{ $producto->tipo_producto->nombre_tipo_producto }}')">
                            <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg"></div>
                            <input type="checkbox" name="producto_fk[]" value="{{ $producto->producto_pk }}" class="relative z-10">
                            <div class="relative z-5 font-bold text-lg">{{ $producto->nombre_producto }}</div>
                            <div class="relative z-5 text-sm">{{ $producto->tipo_producto->nombre_tipo_producto }}</div>
                            <div class="relative z-5 font-semibold">${{ $producto->precio_producto }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Modal para personalizar pizza -->
            <div id="modal-personalizar" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-10 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white">
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-800">Personalizar Pizza</h3>
                        <p id="info-pizza" class="text-sm text-gray-500 mt-1 mb-3">
                            {{-- Información de la pizza personalizable --}}
                        </p>

                        <form id="form-personalizar">
                            <div id="contenedor-ingredientes" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left mb-4">
                                @foreach ($datosIngrediente as $dato)
                                    <div class="space-y-1 border rounded p-2">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" class="checkbox-ingrediente" data-id="{{ $dato->ingrediente_pk }}">
                                            <span class="text-sm text-gray-800">{{ $dato->nombre_ingrediente }}</span>
                                        </label>
                                        <input type="number" class="cantidad-ingrediente w-full border-gray-300 rounded text-sm" 
                                            data-id="{{ $dato->ingrediente_pk }}" 
                                            placeholder="Cantidad en gr/ml" 
                                            disabled min="1">
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="cerrarModalPersonalizar()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">Cancelar</button>
                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                // ====================================================================
                // 1. INICIALIZACIÓN Y ESTADO GLOBAL
                // ====================================================================

                // Objeto global para mantener el estado del pedido en el frontend.
                const selectedProducts = {};
                // Variable para saber qué producto estamos personalizando en el modal.
                let productoSeleccionado = null; 

                // Al cargar la página, llenamos `selectedProducts` con los datos del pedido actual.
                document.addEventListener('DOMContentLoaded', function () {
                    @foreach ($datosPedido->detalle_pedido as $detalle)
                        selectedProducts[{{ $detalle->producto_fk }}] = {
                            name: `{!! addslashes($detalle->producto->nombre_producto) !!}`,
                            price: {{ $detalle->producto->precio_producto }},
                            type: `{!! addslashes($detalle->producto->tipo_producto->nombre_tipo_producto) !!}`,
                            quantity: {{ $detalle->cantidad_producto }},
                            personalizable: {{ $detalle->producto->personalizable ? 'true' : 'false' }},
                            personalizados: [
                                @foreach($detalle->ingredientesPersonalizados as $ingredienteDetalle)
                                    { id: {{ $ingredienteDetalle->ingrediente_fk }}, cantidad: {{ $ingredienteDetalle->cantidad_usada }} },
                                @endforeach
                            ]
                        };
                        fetchStockStatus({{ $detalle->producto_fk }});
                    @endforeach
                    
                    actualizarCambio();
                });

                // ====================================================================
                // 2. FUNCIONES PARA MANEJAR EL RESUMEN DEL PEDIDO
                // ====================================================================

                function toggleProductSelection(div, productId, productName, productPrice, productType) {
                    const esPersonalizable = div.getAttribute('data-personalizable') === '1';

                    // Guardamos el producto seleccionado para el modal
                    productoSeleccionado = { id: productId, name: productName, price: productPrice, type: productType, esNuevo: true };
                    
                    if (esPersonalizable) {
                        limpiarModalPersonalizacion(); 
                        document.getElementById('info-pizza').innerText = `${productName} (${productType}) - $${productPrice.toFixed(2)}`;
                        document.getElementById('modal-personalizar').classList.remove('hidden');
                        return;
                    }

                    // Lógica para productos no personalizables
                    if (!selectedProducts[productId]) {
                        selectedProducts[productId] = { 
                            name: productName, 
                            price: productPrice, 
                            type: productType, 
                            quantity: 1,
                            personalizable: false,
                            personalizados: [] 
                        };
                        addProductToSummary(productId, productName, productPrice, productType, 1, false, []);
                        fetchStockStatus(productId);
                    } else {
                        selectedProducts[productId].quantity++;
                        updateQuantity(productId, selectedProducts[productId].quantity);
                    }
                    updateTotal();
                }

                function removeProductFromSummary(productId) {
                    // Elimina el elemento del DOM.
                    const item = document.getElementById(`order-item-${productId}`);
                    if (item) item.remove();
                    // Elimina los inputs ocultos asociados.
                    const hiddenInputs = document.getElementById(`hidden-inputs-${productId}`);
                    if(hiddenInputs) hiddenInputs.remove();
                    // Elimina el producto de nuestro objeto de estado.
                    delete selectedProducts[productId];
                    
                    updateTotal();
                }

                function updateQuantity(productId, quantity) {
                    const newQuantity = parseInt(quantity, 10) || 1;
                    if (selectedProducts[productId]) {
                        selectedProducts[productId].quantity = newQuantity;
                        
                        // Actualiza el input visual y el oculto.
                        const inputVisual = document.querySelector(`#order-item-${productId} input[type='number']`);
                        const inputHiddenCantidad = document.getElementById(`input-cantidad-${productId}`);
                        
                        if(inputVisual) inputVisual.value = newQuantity;
                        if(inputHiddenCantidad) inputHiddenCantidad.value = newQuantity;
                    }
                    updateTotal();
                }

                // ====================================================================
                // 3. FUNCIONES PARA EL MODAL DE PERSONALIZACIÓN
                // ====================================================================

                // Manejo de ingredientes seleccionados con cantidad
                document.querySelectorAll('.checkbox-ingrediente').forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const ingredienteId = this.dataset.id;
                        const inputCantidad = document.querySelector(`.cantidad-ingrediente[data-id="${ingredienteId}"]`);
                        const seleccionados = document.querySelectorAll('.checkbox-ingrediente:checked');

                        if (seleccionados.length > 4) {
                            this.checked = false;
                            Swal.fire({
                                icon: 'warning',
                                text: 'Solo puedes seleccionar hasta 4 ingredientes',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            return;
                        }

                        inputCantidad.disabled = !this.checked;
                        if (!this.checked) inputCantidad.value = '';
                    });
                });

                function abrirModalParaEditar(productId) {
                    // Obtenemos los datos del producto que ya está en el resumen
                    const producto = selectedProducts[productId];
                    if (!producto) return;

                    // Marcamos que estamos editando un producto existente
                    productoSeleccionado = { id: productId, name: producto.name, price: producto.price, type: producto.type, esNuevo: false };

                    // Llenamos el modal con la información actual
                    document.getElementById('info-pizza').innerText = `${producto.name} (${producto.type}) - $${producto.price.toFixed(2)}`;

                    limpiarModalPersonalizacion();

                    // Pre-seleccionamos los ingredientes que ya tiene
                    producto.personalizados.forEach(ing => {
                        const checkbox = document.querySelector(`.checkbox-ingrediente[data-id="${ing.id}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                            const inputCantidad = document.querySelector(`.cantidad-ingrediente[data-id="${ing.id}"]`);
                            inputCantidad.value = ing.cantidad;
                            inputCantidad.disabled = false;
                        }
                    });
                    
                    document.getElementById('modal-personalizar').classList.remove('hidden');
                }

                document.getElementById('form-personalizar').addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (!productoSeleccionado) return;
                    const { id, name, price, type, esNuevo } = productoSeleccionado;
                    const ingredientesPersonalizados = [];
                    let errores = false;

                    document.querySelectorAll('.checkbox-ingrediente:checked').forEach(checkbox => {
                        const ingredienteId = checkbox.dataset.id;
                        const inputCantidad = document.querySelector(`.cantidad-ingrediente[data-id="${ingredienteId}"]`);
                        const cantidad = parseFloat(inputCantidad.value);

                        if (isNaN(cantidad) || cantidad <= 0) {
                            Swal.fire({ icon: 'warning', text: 'Ingresa una cantidad válida para los ingredientes.', timer: 3000, showConfirmButton: false });
                            errores = true;
                        }
                        ingredientesPersonalizados.push({ id: ingredienteId, cantidad });
                    });

                    if (errores) return;

                    // Actualizamos el objeto global
                    if(esNuevo && !selectedProducts[id]) {
                        selectedProducts[id] = { name, price, type, quantity: 1, personalizable: true, personalizados: ingredientesPersonalizados };
                        addProductToSummary(id, name, price, type, 1, true, ingredientesPersonalizados);
                        fetchStockStatus(id);
                    } else {
                        // Si estamos editando, solo actualizamos los ingredientes
                        selectedProducts[id].personalizados = ingredientesPersonalizados;
                        updateProductInSummary(id);
                    }

                    updateTotal();
                    cerrarModalPersonalizar();
                });

                function cerrarModalPersonalizar() {
                    document.getElementById('modal-personalizar').classList.add('hidden');
                    productoSeleccionado = null;
                    limpiarModalPersonalizacion();
                }

                function limpiarModalPersonalizacion() {
                    document.getElementById('form-personalizar').reset();
                    document.querySelectorAll('.cantidad-ingrediente').forEach(input => input.disabled = true);
                }

                // ====================================================================
                // 4. FUNCIONES PARA ACTUALIZAR LA VISTA (EL DOM)
                // ====================================================================

                function addProductToSummary(id, name, price, type, quantity, esPersonalizable, ingredientes) {
                    const productInputsDiv = document.getElementById('productInputs');
                    
                    // Contenedor principal del item
                    const orderItem = document.createElement('div');
                    orderItem.className = 'order-item p-2 border-b';
                    orderItem.id = `order-item-${id}`;

                    // Contenedor flex para alinear info y controles
                    const flexContainer = document.createElement('div');
                    flexContainer.className = 'flex items-start justify-between gap-4';

                    // Lado izquierdo: Información del producto
                    const infoDiv = document.createElement('div');
                    infoDiv.className = 'flex-grow text-sm';
                    
                    const ingredientesHtml = ingredientes.map(ing => {
                        const el = document.querySelector(`.checkbox-ingrediente[data-id="${ing.id}"] + span`);
                        const nombreIng = el ? el.textContent.trim() : 'Ingrediente';
                        return `${nombreIng} (${ing.cantidad} gr/ml)`;
                    }).join(' | ');

                    infoDiv.innerHTML = `
                        <span id="product-info-${id}"><strong>${name}</strong></span><br>
                        <span>${type} - $${price.toFixed(2)}</span>
                        ${esPersonalizable ? `
                            <div class="mt-1 text-xs text-gray-600" id="ingredientes-display-${id}">
                                <span>${ingredientesHtml || 'Sin ingredientes de pizza personalizada.'}</span>
                            </div>
                            <button type="button" class="edit-btn text-blue-600 hover:underline text-xs mt-1" onclick="abrirModalParaEditar(${id})">Editar Ingredientes</button>
                        ` : ''}
                    `;

                    // Lado derecho: Controles (cantidad y eliminar)
                    const controlsDiv = document.createElement('div');
                    controlsDiv.className = 'flex-shrink-0 w-48 flex items-center justify-between';
                    controlsDiv.innerHTML = `
                        <input type="number" value="${quantity}" min="1" class="w-16 border rounded px-2 py-1 text-center" onchange="updateQuantity(${id}, this.value)">
                        <button type="button" class="text-red-600 hover:underline text-sm" onclick="removeProductFromSummary(${id})">Eliminar</button>
                    `;

                    // Contenedor para inputs ocultos (se añade fuera de la vista principal)
                    const hiddenInputsContainer = document.createElement('div');
                    hiddenInputsContainer.id = `hidden-inputs-${id}`;
                    hiddenInputsContainer.innerHTML = generateHiddenInputs(id, name, quantity, ingredientes);

                    // Ensamblar todo
                    flexContainer.appendChild(infoDiv);
                    flexContainer.appendChild(hiddenInputsContainer);
                    orderItem.appendChild(flexContainer);
                    orderItem.appendChild(controlsDiv);
                    productInputsDiv.appendChild(orderItem);
                }

                // Esta función actualiza un item existente en el resumen
                function updateProductInSummary(id) {
                    const producto = selectedProducts[id];
                    if (!producto) return;

                    const ingredientesHtml = producto.personalizados.map(ing => {
                        const el = document.querySelector(`.checkbox-ingrediente[data-id="${ing.id}"] + span`);
                        const nombreIng = el ? el.textContent.trim() : 'Ingrediente';
                        return `${nombreIng} (${ing.cantidad} gr/ml)`;
                    }).join(' | ');

                    const displayDiv = document.getElementById(`ingredientes-display-${id}`);
                    if(displayDiv) {
                        displayDiv.querySelector('span').innerHTML = ingredientesHtml || 'Sin ingredientes extra.';
                    }
                    
                    const hiddenDiv = document.getElementById(`hidden-inputs-${id}`);
                    if(hiddenDiv) {
                        hiddenDiv.innerHTML = generateHiddenInputs(id, producto.name, producto.quantity, producto.personalizados);
                    }
                }

                function generateHiddenInputs(id, name, quantity, ingredientes) {
                    let inputs = `
                        <input type="hidden" name="productos[${id}][cantidad_producto]" id="input-cantidad-${id}" value="${quantity}">
                        <input type="hidden" name="productos[${id}][nombre_producto]" value="${name}">
                    `;
                    ingredientes.forEach(ing => {
                        inputs += `<input type="hidden" class="ingrediente-personalizado-${id}" name="productos[${id}][ingredientes_personalizados][${ing.id}]" value="${ing.cantidad}">`;
                    });
                    return inputs;
                }

                // Función para verificar el stock
                function fetchStockStatus(productId) {
                    const producto = selectedProducts[productId];
                    if (!producto) return;

                    fetch(`/producto/${productId}/estado-stock`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) { console.error(data.error); return; }

                            let stockMessage = '';
                            if (data.estadoStock === 'En riesgo') {
                                stockMessage = ' <span style="color: red; font-weight: bold;">(Stock en riesgo)</span>';
                            } else if (data.estadoStock === 'Disponible') {
                                stockMessage = ' <span style="color: green; font-weight: bold;">(Stock disponible)</span>';
                            }

                            const productInfoSpan = document.getElementById(`product-info-${productId}`);
                            if (productInfoSpan) {
                                productInfoSpan.innerHTML = `<strong>${producto.name}</strong>${stockMessage}`;
                            }
                        })
                        .catch(error => console.error('Error al obtener el estado del stock:', error));
                }

                // ====================================================================
                // 5. CÁLCULOS Y EVENTOS GENERALES
                // ====================================================================
                
                function updateTotal() {
                    let total = 0;
                    for (const id in selectedProducts) {
                        total += selectedProducts[id].price * selectedProducts[id].quantity;
                    }
                    document.getElementById('totalAmount').textContent = `$ ${total.toFixed(2)}`;
                    document.getElementById('monto_total').value = total.toFixed(2);
                    actualizarCambio();
                }

                function actualizarCambio() {
                    const montoTotal = parseFloat(document.getElementById('monto_total').value) || 0;
                    const pago = parseFloat(document.getElementById('pago').value) || 0;
                    const cambio = pago >= montoTotal ? pago - montoTotal : 0;
                    document.getElementById('cambio').value = cambio.toFixed(2);
                }
                document.getElementById('pago').addEventListener('input', actualizarCambio);

                // Buscador por producto, tipo de producto, y precio
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
