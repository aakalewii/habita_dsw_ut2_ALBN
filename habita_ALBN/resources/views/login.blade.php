@extends('cabecera_auth')

@section('titulo', 'Inicio de Sesión')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('mensaje'))
                <div class="alert alert-info">{{ session('mensaje') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-3 text-center">Iniciar Sesión</h1>

                    <form method="POST" action="{{ route('login.post') }}" class="vstack gap-3">
                        @csrf
                        <div>
                            <label class="form-label" for="email">Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div>
                            <label class="form-label" for="password">Contraseña</label>
                            <input id="password" class="form-control" type="password" name="password" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recuerdame" value="1" id="recuerdame">
                            <label class="form-check-label" for="recuerdame">Recordarme (30 días)</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
