<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('titulo', 'Tienda de muebles')</title>
    @stack('styles')
</head>

<body>
    @php
        $sesionActiva = Session::has('autorizacion_usuario') && Session::get('autorizacion_usuario');
        $usuario = $sesionActiva && Session::has('usuario') ? json_decode(Session::get('usuario')) : null;
    @endphp
    <header>
        <nav>
            <a href="{{ route('principal') }}">Inicio</a>
            <a href="{{ route('preferencias.edit') }}">Preferencias</a>
        </nav>
        @if ($usuario)
            <div>
                {{ $usuario->nombre }}
            </div>
        @endif
        @if ($sesionActiva)
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit">Cerrar sesion</button>
            </form>
        @endif
    </header>

    <main>
        @yield('contenido')
    </main>

    @stack('scripts')
</body>

</html>