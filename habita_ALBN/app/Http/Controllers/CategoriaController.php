<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoriaController extends Controller
{
    private function requireLogin()
    {
        if (!Session::has('autorizacion_usuario') || !Session::get('autorizacion_usuario')) {
            abort(403);
        }
    }

    public function index()
    {
        // Muestra la vista de categorías del panel admin tras verificar login, obteniendo la lista de categorías guardadas en sesión
        $this->requireLogin();
        $categorias = Session::get('categorias', []);
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        // Verifica que el usuario haya iniciado sesión y muestra la vista para crear una nueva categoría.
        $this->requireLogin();
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $this->requireLogin();
        // valida
        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        // genera un id único
        $id = uniqid();
        $categorias = Session::get('categorias', []);
        $categorias[$id] = array_merge(['id' => $id], $data);
        Session::put('categorias', $categorias);
        // guarda en sesión y redirige al listado
        return redirect()->route('categorias.index');
    }

    public function show(string $id)
    {
        $this->requireLogin();
        $categorias = Session::get('categorias', []);
        if (!isset($categorias[$id])) abort(404); // Busca la categoría por ID en sesión. Si no existe lanza error 404
        $categoria = $categorias[$id];
        // Si existe, muestra su vista de detalle.
        return view('admin.categorias.show', compact('categoria'));
    }

    public function edit(string $id)
    {
        $this->requireLogin();
        $categorias = Session::get('categorias', []);
        if (!isset($categorias[$id])) abort(404);
        $categoria = $categorias[$id];
        // Realiza lo mismo que el anterior método lo que devuelve la vista de edición
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, string $id)
    {
        $this->requireLogin();
        $categorias = Session::get('categorias', []);
        if (!isset($categorias[$id])) abort(404);

        // Valida
        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        $categorias[$id] = array_merge(['id' => $id], $data);
        Session::put('categorias', $categorias);
        // Actualiza en sesión

        return redirect()->route('categorias.index');
    }

    public function destroy(string $id)
    {
        $this->requireLogin();
        $categorias = Session::get('categorias', []);
        unset($categorias[$id]);
        Session::put('categorias', $categorias);
        // Elimina de la sesion la categoria y redirige al listado
        return redirect()->route('categorias.index');
    }
}
