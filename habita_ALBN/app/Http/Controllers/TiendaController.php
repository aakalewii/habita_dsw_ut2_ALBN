<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mueble;
use Illuminate\Support\Facades\Cookie; // Necesario para leer preferencias y stock

class TiendaController extends Controller
{
    public function index(Request $request)
    {
        // 1. LECTURA DE PREFERENCIAS (R1.c, R1.b)
        $itemsPerPage = (int)$request->cookie('preferencia_paginacion', 12);
        $monedaSimbolo = $request->cookie('preferencia_moneda', 'EUR');
        $page = $request->query('page', 1);

        // 2. CONSTRUCCIÓN DEL CATÁLOGO LEYENDO MOCKS Y SOBREESCRIBIENDO CON COOKIES
        // Obtenemos los datos base (Mock Data)
        $muebles = Mueble::getAllMockData();
        $mueblesConStockActualizado = [];
        
        foreach ($muebles as $id => $mueble) {
            $cookieName = "mueble_{$id}";
            $cookieData = $request->cookie($cookieName); // Leemos la cookie de persistencia individual
            
            // Si el Admin o una compra anterior actualizó el stock, la cookie existe
            if ($cookieData) {
                $arr = json_decode($cookieData, true);
                
                // CORRECCIÓN CRÍTICA: Sobreescribir el stock del Mock con el valor de la Cookie
                if (isset($arr['stock'])) {
                    $mueble['stock'] = $arr['stock']; 
                }
            }
            
            // Usamos un array de Mueble para pasarlo a la paginación (aunque sea un array asociativo)
            $mueblesConStockActualizado[$id] = $mueble;
        }


        // 3. LÓGICA DE PAGINACIÓN MANUAL (R1.c, R3.b.iii)
        $mueblesArray = array_values($mueblesConStockActualizado);
        $totalItems = count($mueblesArray);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;
        
        // El array_slice mantiene solo los elementos de la página actual
        $mueblesPaginated = array_slice($mueblesArray, $offset, $itemsPerPage, true);

        // 4. PREPARAR VARIABLES PARA LA VISTA
        $currentQuery = $request->query(); // Obtener todos los parámetros de la URL para la paginación

        return view('principal', compact(
            'mueblesPaginated', 
            'monedaSimbolo', 
            'page', 
            'totalPages', 
            'totalItems', 
            'currentQuery'
        ));
    }

    public function show(Request $request, string $id)
    {
        $val = $request->cookies->get("mueble_{$id}");
        if (!$val) {
            abort(404);
        }

        $arr = json_decode($val, true);
        if (!is_array($arr)) {
            abort(404);
        }

        // Se mantiene la lógica original de tu compañero para cargar el detalle desde la cookie
        $mueble = new Mueble(
            $arr['id'],
            $arr['nombre'] ?? '',
            $arr['categoria_id'] ?? [],
            $arr['descripcion'] ?? null,
            $arr['precio'] ?? 0,
            $arr['stock'] ?? 0,
            $arr['materiales'] ?? null,
            $arr['dimensiones'] ?? null,
            $arr['color_principal'] ?? null,
            $arr['destacado'] ?? false,
            []
        );

        return view('catalogomuebles.show', compact('mueble'));
    }
}