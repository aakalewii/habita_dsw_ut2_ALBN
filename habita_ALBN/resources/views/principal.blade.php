@extends('cabecera')

@section('contenido')
    <h2 class="mb-4">Catálogo de Muebles</h2>
    
    <div class="row">
        @php
            // MOCK de Muebles (Mismos datos que en CarritoController)
            $muebles = [
                'MESA1' => ['id' => 'MESA1', 'nombre' => 'Mesa de Comedor Lusso', 'precio' => 250.00, 'stock' => 5],
                'SOFA2' => ['id' => 'SOFA2', 'nombre' => 'Sofá Modular Confort', 'precio' => 850.00, 'stock' => 12],
                'SILLA3' => ['id' => 'SILLA3', 'nombre' => 'Silla Eames Clásica', 'precio' => 75.00, 'stock' => 0], // Agotado
            ];
        @endphp

        @foreach ($muebles as $mueble)
            <div class="col-md-3 mb-4"> {{-- Usamos col-md-3 para 4 columnas --}}
                <div class="card h-100">
                    {{-- Simulación de Imagen --}}
                    <img src="https://via.placeholder.com/200x150/{{ $mueble['id'] }}" class="card-img-top" alt="{{ $mueble['nombre'] }}"> 
                    
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">{{ $mueble['nombre'] }}</h5>
                            <p><strong>{{ number_format($mueble['precio'], 2) }} €</strong></p>
                            <p class="text-muted">Stock: {{ $mueble['stock'] }}</p>
                        </div>

                        {{-- Formulario de Añadir al carrito (Requerimiento 4.a) --}}
                        <form method="POST" action="{{ route('carrito.add', ['muebleId' => $mueble['id']]) }}">
                            @csrf
                            
                            {{-- Contenedor para Input/Botón --}}
                            <div class="d-grid gap-2"> 
                                <input class="form-control text-center" 
                                       type="number" 
                                       name="cantidad" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $mueble['stock'] }}" 
                                       @if($mueble['stock'] == 0) disabled @endif
                                       required>
                                
                                <button class="btn btn-primary mt-2" 
                                        type="submit" 
                                        @if($mueble['stock'] == 0) disabled @endif>
                                    Agregar al carrito
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection