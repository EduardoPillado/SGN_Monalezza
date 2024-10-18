<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('img/monalezza.ico') }}" rel="icon">
    <title>La Monalezza Pizzeria - Login</title>
    <link rel="stylesheet" href="{{ asset('css/login_style.css') }}">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="justify-content: center; align-items: center; display: flex; height: 100vh; margin: 0;">
    @include('mensaje')
    
    <div class="login-container">
        <img src="{{ asset('img/logo_lamonalezza.webp') }}" alt="La Monalezza Pizzeria" class="logo">
        <form action="{{ route('usuario.login') }}" method="POST">
            @csrf

            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contraseña" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>

</html>