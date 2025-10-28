<h1>Categorías</h1>
<p><a href="{{ route('categorias.create') }}">+ Nueva categoría</a></p>
@if (empty($categorias))
<p>No hay categorías.</p>
@else
<table border="1" cellpadding="6" cellspacing="0">
<thead>
<tr>
<th>Nombre</th>
<th>Descripción</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
@foreach ($categorias as $categoria)
<tr>
<td>{{ $categoria['nombre'] }}</td>
<td>{{ $categoria['descripcion'] ?? '-' }}</td>
<td>
<a href="{{ route('categorias.show', $categoria['id']) }}">Ver</a> |
<a href="{{ route('categorias.edit', $categoria['id']) }}">Editar</a> |
<form method="POST" action="{{ route('categorias.destroy', $categoria['id']) }}" style="display:inline">
@csrf
@method('DELETE')
<button type="submit" onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>
@endif