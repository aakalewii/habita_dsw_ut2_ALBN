<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class PreferenciasController extends Controller
{
    public function edit()
    {
        // Comprobar autenticación manualmente
        if (!Session::has('autorizacion_usuario')) {
            return redirect()->route('login');
        }

        $moneda = Cookie::get('preferencia_moneda', '€');
        $paginacion = Cookie::get('preferencia_paginacion', '12');
        $tema = Cookie::get('preferencia_tema', 'claro');

        return view('preferencias', [
            'moneda' => $moneda,
            'paginacion' => $paginacion,
            'tema' => $tema,
        ]);
    }

    public function update(Request $request)
    {
        if (!Session::has('autorizacion_usuario')) {
            return redirect()->route('login');
        }

        $minutes = 60 * 24 * 30;
        Cookie::queue('preferencia_moneda', $request->moneda, $minutes);
        Cookie::queue('preferencia_paginacion', $request->paginacion, $minutes);
        Cookie::queue('preferencia_tema', $request->tema, $minutes);

        return redirect()->back()->with('mensaje', 'Preferencias guardadas correctamente');
    }
}
