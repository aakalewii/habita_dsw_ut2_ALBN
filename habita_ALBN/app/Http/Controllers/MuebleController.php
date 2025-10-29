<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Mueble;

class MuebleController extends Controller
{
    private function toMueble($data): Mueble
    {
        if ($data instanceof Mueble) {
            return $data;
        }
        return new Mueble(
            $data['id'] ?? uniqid(),
            $data['nombre'] ?? '',
            $data['categoria_id'] ?? [],
            $data['descripcion'] ?? null,
            $data['precio'] ?? 0,
            $data['stock'] ?? 0,
            $data['materiales'] ?? null,
            $data['dimensiones'] ?? null,
            $data['color_principal'] ?? null,
            $data['destacado'] ?? false,
            $data['imagenes'] ?? []
        );
    }

    private function requireLogin()
    {
        if (!Session::has('autorizacion_usuario') || !Session::get('autorizacion_usuario')) {
            abort(403);
        }
    }

    public function index()
    {
        $this->requireLogin();
        $raw = Session::get('muebles', []);
        $muebles = [];
        foreach ($raw as $id => $mueble) {
            $muebles[$id] = $this->toMueble($mueble);
        }
        return view('admin.muebles.index', compact('muebles'));
    }

    public function create()
    {
        $this->requireLogin();
        return view('admin.muebles.create');
    }

    public function store(Request $request)
    {
        $this->requireLogin();
        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'materiales' => 'nullable|string',
            'dimensiones' => 'nullable|string',
            'color_principal' => 'nullable|string',
        ]);
        $data['destacado'] = $request->boolean('destacado');

        $id = uniqid();
        $muebles = Session::get('muebles', []);
        $muebles[$id] = new Mueble(
            $id,
            $data['nombre'],
            [],
            $data['descripcion'] ?? null,
            $data['precio'],
            $data['stock'],
            $data['materiales'] ?? null,
            $data['dimensiones'] ?? null,
            $data['color_principal'] ?? null,
            $data['destacado'] ?? false,
            []
        );
        Session::put('muebles', $muebles);

        return redirect()->route('muebles.index');
    }

    public function show(string $id)
    {
        $this->requireLogin();
        $muebles = Session::get('muebles', []);
        if (!isset($muebles[$id])) abort(404);
        $mueble = $this->toMueble($muebles[$id]);
        return view('admin.muebles.show', compact('mueble'));
    }

    public function edit(string $id)
    {
        $this->requireLogin();
        $muebles = Session::get('muebles', []);
        if (!isset($muebles[$id])) abort(404);
        $mueble = $this->toMueble($muebles[$id]);
        return view('admin.muebles.edit', compact('mueble'));
    }

    public function update(Request $request, string $id)
    {
        $this->requireLogin();
        $muebles = Session::get('muebles', []);
        if (!isset($muebles[$id])) abort(404);

        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'materiales' => 'nullable|string',
            'dimensiones' => 'nullable|string',
            'color_principal' => 'nullable|string',
        ]);
        $data['destacado'] = $request->boolean('destacado');

        $muebles[$id] = new Mueble(
            $id,
            $data['nombre'],
            [],
            $data['descripcion'] ?? null,
            $data['precio'],
            $data['stock'],
            $data['materiales'] ?? null,
            $data['dimensiones'] ?? null,
            $data['color_principal'] ?? null,
            $data['destacado'] ?? false,
            []
        );
        Session::put('muebles', $muebles);

        return redirect()->route('muebles.index');
    }

    public function destroy(string $id)
    {
        $this->requireLogin();
        $muebles = Session::get('muebles', []);
        unset($muebles[$id]);
        Session::put('muebles', $muebles);
        return redirect()->route('muebles.index');
    }
}
