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
        // Si no existe la clave de sesión 'autorizacion_usuario' redirigimos al login
        if (!Session::has('autorizacion_usuario')) {
            // Usuario no autorizado -> redirección a la ruta de login
            return redirect()->route('login');
        }

        // Leer cookies con valores por defecto si no existen
        // 'preferencia_moneda' -> símbolo de moneda (por defecto '€')
        $moneda = Cookie::get('preferencia_moneda', '€');
        // 'preferencia_paginacion' -> número de items por página (por defecto '12')
        $paginacion = Cookie::get('preferencia_paginacion', '12');
        // 'preferencia_tema' -> tema de la UI (por defecto 'claro')
        $tema = Cookie::get('preferencia_tema', 'claro');

        // Renderizar la vista 'preferencias' pasando las variables obtenidas
        return view('preferencias', [
            'moneda' => $moneda,
            'paginacion' => $paginacion,
            'tema' => $tema,
        ]);
    }

    public function update(Request $request)
    {
        // Comprobar autenticación manualmente antes de permitir guardar
        if (!Session::has('autorizacion_usuario')) {
            // Redirigir al login si no está autorizado
            return redirect()->route('login');
        }

        // Duración de las cookies en minutos (30 días)
        $minutes = 60 * 24 * 30;

        // Guardar las preferencias recibidas desde el request en cookies
        Cookie::queue('preferencia_moneda', $request->moneda, $minutes);
        Cookie::queue('preferencia_paginacion', $request->paginacion, $minutes);
        Cookie::queue('preferencia_tema', $request->tema, $minutes);

        // Volver a la página anterior con un mensaje flash informando éxito
        return redirect()->back()->with('mensaje', 'Preferencias guardadas correctamente');
    }
}
