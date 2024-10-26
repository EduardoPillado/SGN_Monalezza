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

    <div class="main-container">
        <div class="content-container">
            <!-- Columna izquierda -->
            <div class="left-column">
                <div class="order-summary">
                    <form action="{{ route('pedido.guardar') }}" method="POST">
                        @csrf
                        
                        <!-- Cliente -->
                        <div class="form-group">
                            <label for="cliente">Cliente:</label>
                            <select name="cliente_fk" id="cliente" class="form-control">
                                <option value="">Selecciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Empleado -->
                        <div class="form-group">
                            <label for="empleado">Empleado:</label>
                            <select name="empleado_fk" id="empleado" class="form-control">
                                <option value="">Selecciona un empleado</option>
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id }}">{{ $empleado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fecha y hora del pedido -->
                        <div class="form-group">
                            <label for="fecha_hora_pedido">Fecha y Hora del Pedido:</label>
                            <input type="datetime-local" name="fecha_hora_pedido" id="fecha_hora_pedido" class="form-control">
                        </div>

                        <!-- Medio del pedido -->
                        <div class="form-group">
                            <label for="medio_pedido">Medio del Pedido:</label>
                            <select name="medio_pedido_fk" id="medio_pedido" class="form-control">
                                <option value="">Selecciona un medio</option>
                                @foreach($mediosPedido as $medio)
                                    <option value="{{ $medio->id }}">{{ $medio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Monto total -->
                        <div class="form-group">
                            <label for="monto_total">Monto Total:</label>
                            <input type="number" step="0.01" name="monto_total" id="monto_total" class="form-control" placeholder="0.00">
                        </div>

                        <!-- Número de transacción -->
                        <div class="form-group">
                            <label for="numero_transaccion">Número de Transacción (opcional):</label>
                            <input type="text" name="numero_transaccion" id="numero_transaccion" class="form-control" placeholder="Número de transacción">
                        </div>

                        <!-- Tipo de pago -->
                        <div class="form-group">
                            <label for="tipo_pago">Tipo de Pago:</label>
                            <select name="tipo_pago_fk" id="tipo_pago" class="form-control">
                                <option value="">Selecciona un tipo de pago</option>
                                @foreach($tiposPago as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Notas de la remisión -->
                        <div class="form-group">
                            <label for="notas_remision">Notas:</label>
                            <textarea name="notas_remision" id="notas_remision" class="form-control" rows="3" placeholder="Notas adicionales"></textarea>
                        </div>

                        <!-- Botón para enviar el formulario -->
                        <div class="form-group">
                            <button type="submit" class="numpad-button numpad-button-cobrar">Guardar Pedido</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="right-column">
                <!-- Tu contenido para la columna derecha permanece igual -->
                <div class="menu-grid">
                    <div class="menu-item">
                        <div>Pizza #1</div>
                        <div>$170</div>
                        <div>🍕</div>
                    </div>
                    <div class="menu-item">
                        <div>Pizza #2</div>
                        <div>$190</div>
                        <div>🍕</div>
                    </div>
                    <div class="menu-item">
                        <div>Pizza #3</div>
                        <div>$200</div>
                        <div>🍕</div>
                    </div>
                    <div class="menu-item">
                        <div>Pizza #4</div>
                        <div>$250</div>
                        <div>🍕</div>
                    </div>
                    <div class="menu-item">
                        <div>Pizza #5</div>
                        <div>$250</div>
                        <div>🍕</div>
                    </div>
                </div>
                <div class="info-buttons">
                    <a href="{{ route('producto.mostrar') }}">
                        <div class="info-button products-registered">
                            Productos registrados 🍕
                        </div>
                    </a>
                    <div class="info-button total-sales">
                        Total ventas 💰
                    </div>
                    <div class="info-button profits">
                        Ganancias 💼
                    </div>
                    <div class="info-button low-stock">
                        Productos poco Stock 📉
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
