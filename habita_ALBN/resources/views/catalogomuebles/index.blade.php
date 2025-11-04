@extends('cabecera')

@section('titulo', 'Catálogo de Muebles')

@section('contenido')
    <h2 class="mb-4">Catálogo de Muebles ({{ $totalItems }} ítems)</h2>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    @if (empty($mueblesPaginated))
        <div class="alert alert-info">No hay muebles disponibles.</div>
    @else
        <div class="row">
            @foreach ($mueblesPaginated as $mueble)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="https://via.placeholder.com/200x150/{{ $mueble->getId() }}" class="card-img-top" alt="{{ $mueble->getNombre() }}"> 
                        
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title">{{ $mueble->getNombre() }}</h5>
                                <p><strong>{{ number_format($mueble->getPrecio(), 2) }} {{ $monedaSimbolo }}</strong></p>
                                <p class="text-muted">Stock: {{ $mueble->getStock() }}</p>
                            </div>

                            {{-- Formulario para añadir al carrito (R4.a) --}}
                            <form method="POST" action="{{ route('carrito.add', ['muebleId' => $mueble->getId()]) }}">
                                @csrf
                                <div class="d-grid gap-2"> 
                                    <input class="form-control text-center" 
                                           type="number" 
                                           name="cantidad" 
                                           value="1" 
                                           min="1" 
                                           max="{{ $mueble->getStock() }}" 
                                           style="width: 100px; margin: 0 auto;"
                                           @if($mueble->getStock() == 0) disabled @endif
                                           required>
                                    
                                    <button class="btn btn-primary mt-2" 
                                            type="submit" 
                                            @if($mueble->getStock() == 0) disabled @endif>
                                        Agregar al carrito
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Controles de Paginación (R1.c, R3.b.iii) --}}
        @if ($totalPages > 1)
            <nav aria-label="Paginación">
                <ul class="pagination justify-content-center">
                    {{-- Botón Anterior --}}
                    <li class="page-item @if($page <= 1) disabled @endif">
                        <a class="page-link" href="{{ route('catalogomuebles.index', array_merge($currentQuery, ['page' => $page - 1])) }}">Anterior</a>
                    </li>
                    
                    {{-- Números de página --}}
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item @if($i == $page) active @endif">
                            <a class="page-link" href="{{ route('catalogomuebles.index', array_merge($currentQuery, ['page' => $i])) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Botón Siguiente --}}
                    <li class="page-item @if($page >= $totalPages) disabled @endif">
                        <a class="page-link" href="{{ route('catalogomuebles.index', array_merge($currentQuery, ['page' => $page + 1])) }}">Siguiente</a>
                    </li>
                </ul>
            </nav>
        @endif
    @endif
@endsection