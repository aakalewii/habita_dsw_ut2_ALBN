<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mueble;
use Illuminate\Support\Facades\Cookie; 
use App\Http\Controllers\CarritoController; // Usado para acceder al mock base

class TiendaController extends Controller
{
    public function index(Request $request)
    {
        // 1. LECTURA DE PREFERENCIAS (R1.c, R1.b)
        $itemsPerPage = (int)$request->cookie('preferencia_paginacion', 12);
        $monedaSimbolo = $request->cookie('preferencia_moneda', '€'); // Usamos € por defecto
        $page = $request->query('page', 1);

        // 2. CONSTRUCCIÓN DEL CATÁLOGO LEYENDO MOCKS Y COOKIES
        // Usamos el MockData de Mueble como fuente base
        $mueblesBase = Mueble::getAllMockData(); 
        $mueblesFinal = [];
        
        foreach ($mueblesBase as $id => $mueble) {
            $cookieName = "mueble_{$id}";
            $cookieData = $request->cookie($cookieName); 
            
            // Si hay una cookie de stock actualizada (después de añadir/editar)
            if ($cookieData) {
                $arr = json_decode($cookieData, true);
                
                // CRÍTICO: Sobreescribir el stock del Mock con el valor de la Cookie
                if (isset($arr['stock'])) {
                    $mueble['stock'] = $arr['stock']; 
                }
            }
            
            // Construimos el objeto Mueble con el stock final (actualizado o base)
            $mueblesFinal[$id] = new Mueble(
                $mueble['id'], $mueble['nombre'] ?? '', $mueble['categoria_id'] ?? [], $mueble['descripcion'] ?? null,
                $mueble['precio'] ?? 0, $mueble['stock'] ?? 0, 
                $mueble['materiales'] ?? null, $mueble['dimensiones'] ?? null, $mueble['color_principal'] ?? null,
                $mueble['destacado'] ?? false, []
            );
        }

        // 3. LÓGICA DE PAGINACIÓN MANUAL (R1.c, R3.b.iii)
        $mueblesArray = array_values($mueblesFinal);
        $totalItems = count($mueblesArray);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;
        $mueblesPaginated = array_slice($mueblesArray, $offset, $itemsPerPage, true);

        // Pasamos todos los datos necesarios
        $currentQuery = $request->query(); 
        return view('principal', compact(
            'mueblesPaginated', 'monedaSimbolo', 'page', 'totalPages', 'totalItems', 'currentQuery'
        ));
    }

    public function show(Request $request, string $id)
    {
        // Esta función lee el detalle del mueble, que automáticamente tendrá el stock actualizado desde la cookie.
        $val = $request->cookies->get("mueble_{$id}");
        if (!$val) { abort(404); }

        $arr = json_decode($val, true);
        if (!is_array($arr)) { abort(404); }

        $mueble = new Mueble(
            $arr['id'], $arr['nombre'] ?? '', $arr['categoria_id'] ?? [], $arr['descripcion'] ?? null,
            $arr['precio'] ?? 0, $arr['stock'] ?? 0, 
            $arr['materiales'] ?? null, $arr['dimensiones'] ?? null, $arr['color_principal'] ?? null,
            $arr['destacado'] ?? false, []
        );

        return view('catalogomuebles.show', compact('mueble'));
    }
}