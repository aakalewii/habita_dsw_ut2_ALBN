<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class AdministracionController extends Controller
{
    public function index()
    {
        if (!Session::has('autorizacion_usuario') || !Session::get('autorizacion_usuario')) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        $usuario = json_decode(Session::get('usuario'));

        return view('dashboard', compact('usuario'));
    }

    public function principal()
    {
        if (!Session::has('autorizacion_usuario') || !Session::get('autorizacion_usuario')) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        $usuario = json_decode(Session::get('usuario'));

        return view('principal', compact('usuario'));
    }
}
