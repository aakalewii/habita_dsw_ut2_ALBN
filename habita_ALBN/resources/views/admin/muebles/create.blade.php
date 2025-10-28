@section('titulo', 'Nueva receta')

@section('contenido')
    <h2>Nuevo Producto</h2>

    <form method="POST" action="{{ route('receta.store') }}">
        @csrf
        <input type="text" name="titulo" placeholder="Título" required>
        <input type="number" name="dificultad" min="1" max="5" placeholder="Dificultad (1-5)" required>
        <input type="number" step="0.1" name="precio" placeholder="Precio (€)" required>

        <h3>Categoria</h3>
        <div id="ingredientes">
            <div>
                <input type="text" name="categoria_id[0][nombre]" placeholder="Nombre" required>
                <input type="text" name="categoria_id[0][descripcion]" placeholder="Descripción">
            </div>
        </div>

        <button class="btn-agregar" type="button" onclick="agregarCategoria()">+ Añadir categoria</button>
        <button class="btn-guardar" type="submit">Guardar receta</button>
    </form>
    <br>
    <a href="{{ url()->previous() }}" class="btn-volver">
        Volver
    </a>
    <script>
        let count = 1;
        // Función de JavaScript que agrega ingredientes de forma dinámica.
        function agregarIngrediente() {
            const div = document.createElement('div');
            div.innerHTML = `
            <input type="text" name="categoria_id[${count}][nombre]" placeholder="Nombre" required>
            <input type="text" name="categoria_id[${count}][descripcion]" placeholder="Descripción">
        `;
            document.getElementById('categoria_id').appendChild(div);
            count++;
        }
    </script>
@endsection
