<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Enums\RolUser;

class AdministracionController extends Controller
{
    public function index()
    {
        // Validar autorización en sesión
        if (!Session::has('autorizacion_usuario') || !Session::get('autorizacion_usuario')) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        // Validar que exista usuario en sesión
        $usuarioJson = Session::get('usuario');
        if (!$usuarioJson) {
            return redirect()->route('login')->withErrors(['error' => 'Sesión inválida. Inicia sesión.']);
        }

        $usuario = json_decode($usuarioJson);

        // Validar rol administrador para acceder al dashboard
        if (!isset($usuario->rol) || $usuario->rol !== RolUser::ADMIN->value) {
            abort(403, 'Acceso denegado. Se requiere rol de Administrador.');
        }

        return view('dashboard', compact('usuario'));
    }

    public function principal()
    {
        // Acceso público: redirige al catálogo
        $usuario = Session::has('usuario') ? json_decode(Session::get('usuario')) : null;
        return redirect()->route('catalogomuebles.index');
    }
}

