<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tienda de Muebles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        /* Estilos básicos para la visualización */
        .card-body form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .totales-carrito {
            text-align: right;
            margin-top: 20px;
        }
        .totales-carrito h4 {
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-light">
    {{-- BARRA DE NAVEGACIÓN --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a href="{{ route('principal') }}" class="navbar-brand">🏠 Tienda de Muebles</a>
            
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="navbar-text text-white-50">
                    Usuario Activo 
                </span>
                
                <a href="{{ route('carrito.show') }}" class="btn btn-outline-light">Ver Carrito</a>
                
                {{-- Formulario para Cerrar Sesión --}}
                <form action="{{ route('logout') }}" method="POST" class="d-flex"> 
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