<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>Sistema de Gestión de Pizzería</title>
</head>
<body class="pizza-body" x-data="{ sidebarOpen: false }">
    @include('sidebar')

    <div class="main-container">
        <div class="content-container">
            <!-- Columna izquierda -->
            <div class="left-column">
                <div class="order-summary">
                    <div class="order-items">
                        <div class="order-item">
                            <span>Pizza #1 🍕</span>
                            <span>$170</span>
                        </div>
                        <div class="order-item">
                            <span>Pizza #2 🍕</span>
                            <span>$190</span>
                        </div>
                        <div class="order-item">
                            <span>Pizza #3 🍕</span>
                            <span>$200</span>
                        </div>
                    </div>
                    <div class="order-total">
                        <span>Total:</span>
                        <span>$ 560.00</span>
                    </div>
                </div>
                <div class="numpad-container">
                    <button class="numpad-button">1</button>
                    <button class="numpad-button">2</button>
                    <button class="numpad-button">3</button>
                    <button class="numpad-button">+/-</button>
                    <button class="numpad-button">4</button>
                    <button class="numpad-button">5</button>
                    <button class="numpad-button">6</button>
                    <button class="numpad-button">0</button>
                    <button class="numpad-button">7</button>
                    <button class="numpad-button">8</button>
                    <button class="numpad-button">9</button>
                    <button class="numpad-button">&lt;</button>
                    <button class="numpad-button numpad-button-cobrar">Cobrar</button>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="right-column">
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
                    <div class="info-button products-registered">Productos registrados 🍕</div>
                    <div class="info-button total-sales">Total ventas 💰</div>
                    <div class="info-button profits">Ganancias 💼</div>
                    <div class="info-button low-stock">Productos poco Stock 📉</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>