@extends('cabecera')

@section('titulo', 'Nuevo mueble')

@section('contenido')
    <h2>Nuevo mueble</h2>

    <form method="POST" action="{{ route('muebles.store') }}">
        @csrf
        <input type="text" name="nombre" placeholder="nombre" required>
        <label for="categoria_id">Categoría</label>
        <select name="categoria_id[]" id="categoria_id">
            @forelse ($categorias as $cat)
            {{-- Para cada categoría de la lista, se añade una opción al menú desplegable usando
             su id como valor y su nombre como texto visible. --}}
                <option value="{{ $cat['id'] }}">{{ $cat['nombre'] }}</option>
            @empty
            {{-- Si no hay categoria muestra esto --}}
                <option value="">Sin categoría</option>
            @endforelse
        </select>
        <input type="number" step="0.1" name="precio" placeholder="Precio (€)" required>
        <input type="number" name="stock" placeholder="stock" required>
        <input type="text" name="materiales" placeholder="materiales" required>
        <input type="text" name="dimensiones" placeholder="dimensiones" required>
        <input type="text" name="color_principal" placeholder="color principal" required>
        <label>
            <input type="checkbox" name="destacado" value="1">
            Destacado
        </label>  
        <button class="btn-guardar" type="submit">Guardar mueble</button>
    </form>
    <br>
    <a href="{{ url()->previous() }}" class="btn-volver">
        Volver
    </a>
@endsection
