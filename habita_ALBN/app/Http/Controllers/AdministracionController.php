<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class AdministracionController extends Controller
{
    public function index()
    {
        // Esta función protege el DASHBOARD y DEBE mantener la protección.
        if (!Session::has('autorizacion_usuario') || !Session::get('autorizacion_usuario')) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        $usuario = json_decode(Session::get('usuario'));

        return view('dashboard', compact('usuario'));
    }

    public function principal()
    {

        // Si la sesión existe, la pasamos a la vista; si no, es null.
        $usuario = Session::has('usuario') ? json_decode(Session::get('usuario')) : null;

        return view('principal', compact('usuario'));
    }
}