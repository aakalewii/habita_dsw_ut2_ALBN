<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de Sesión</title>
</head>

<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <!-- obtenemos el error a través del identificador, estructura propia de Laravel -->
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <p> {{ $error }} </p>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('mensaje'))
            <div class="mensaje">{{ session('mensaje') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div>
                <label>Email</label>
                <input type="email" name="email" value="" required>
            </div>
            <div>
                <label>Contraseña</label>
                <input type="password" name="password" value="" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        @if($mensaje_error)
            <p style="color:red;">{{ $mensaje_error }}</p>
        @endif
    </div>
</body>

</html>
