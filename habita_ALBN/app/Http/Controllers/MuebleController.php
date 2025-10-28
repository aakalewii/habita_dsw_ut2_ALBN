<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MuebleController extends Controller
{
    private function requireLogin()
    {
        if (!Session::has('autorizacion_usuario') || !Session::get('autorizacion_usuario')) {
            abort(403);
        }
    }

    public function index()
    {
        $this->requireLogin();
        $muebles = Session::get('muebles', []);
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
        $muebles[$id] = array_merge(['id' => $id], $data);
        Session::put('muebles', $muebles);

        return redirect()->route('muebles.index');
    }

    public function show(string $id)
    {
        $this->requireLogin();
        $muebles = Session::get('muebles', []);
        if (!isset($muebles[$id])) abort(404);
        $mueble = $muebles[$id];
        return view('admin.muebles.show', compact('mueble'));
    }

    public function edit(string $id)
    {
        $this->requireLogin();
        $muebles = Session::get('muebles', []);
        if (!isset($muebles[$id])) abort(404);
        $mueble = $muebles[$id];
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

        $muebles[$id] = array_merge(['id' => $id], $data);
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
