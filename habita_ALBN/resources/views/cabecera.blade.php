<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tienda de Muebles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        /* Ajuste para que los botones de carrito no ocupen todo el ancho en el catálogo */
        .card-body form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>

<body class="bg-light">
    {{-- BARRA DE NAVEGACIÓN --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a href="{{ route('principal') }}" class="navbar-brand">🏠 Tienda de Muebles</a>
            
            {{-- Elementos de la derecha: Usaremos ml-auto para empujar al final --}}
            <div class="ms-auto d-flex align-items-center gap-3">
                
                {{-- Esto simula el nombre de usuario/admin si existiera --}}
                <span class="navbar-text text-white-50">
                    {{-- Si tuvieras la variable $usuario disponible, iría aquí --}}
                    Usuario Activo 
                </span>
                
                <a href="{{ route('carrito.show') }}" class="btn btn-outline-light">Ver Carrito</a>
                
                {{-- Simulación del botón Cerrar Sesión --}}
                <form action="#" method="POST" class="d-flex"> 
                    @csrf
                    <button class="btn btn-outline-danger" type="submit">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        @yield('contenido')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>