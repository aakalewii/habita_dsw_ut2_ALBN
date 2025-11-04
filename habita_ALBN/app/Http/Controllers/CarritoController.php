<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\User; 
use App\Models\Mueble;

class CarritoController extends Controller
{
    const COOKIE_MINUTES = 60 * 24 * 30; 
    const ANONYMOUS_KEY = 'carrito_anonimo'; 

    private function getStorageIdentifier(): string
    {
        if (Session::has('usuario')) {
            $usuarioData = json_decode(Session::get('usuario'));
            return 'carrito_user_' . md5($usuarioData->email); 
        }
        return self::ANONYMOUS_KEY;
    }

    private function getCarritoFromStorage(Request $request): array
    {
        $key = $this->getStorageIdentifier();
        if (Session::has($key)) { return Session::get($key, []); }
        $cookie_data = $request->cookie($key);
        if ($cookie_data) {
            $carrito = json_decode($cookie_data, true);
            Session::put($key, $carrito);
            return $carrito;
        }
        return [];
    }

    private function saveCarritoToStorage(array $carrito)
    {
        $key = $this->getStorageIdentifier();
        Session::put($key, $carrito); 
        Cookie::queue($key, json_encode($carrito), self::COOKIE_MINUTES);
    }

    /**
     * FUNCIÓN CORREGIDA: Asegura que la cookie del mueble se cree o se actualice
     * con todos los datos (no solo el stock) para que el TiendaController no falle.
     */
    private function updateMuebleStockInCookie(string $id, int $newStock)
    {
        $cookieName = "mueble_{$id}";
        $cookieData = json_decode(request()->cookie($cookieName), true); 
        
        // Si la cookie no existe (Admin nunca lo guardó), usamos el MockData completo como base
        if (!is_array($cookieData)) {
            $cookieData = Mueble::getAllMockData()[$id] ?? null;
            
            if (!$cookieData) return; // Si no está ni en la cookie ni en el mock, salimos.
        }
        
        // Actualizamos el stock
        $cookieData['stock'] = $newStock;
        
        // Guardamos la cookie completa con el stock actualizado
        Cookie::queue($cookieName, json_encode($cookieData), self::COOKIE_MINUTES);
    }
    
    // Función auxiliar para obtener datos de un mueble (LEYENDO DE MOCK/COOKIE)
    private function getMuebleById(string $id): ?array
    {
    // 1. Obtener los datos base (Mock)
    $mueblesMock = Mueble::getAllMockData();
    $mueble = $mueblesMock[$id] ?? null; // Almacenamos el mueble aquí

    // 2. Si el mueble existe en el mock, intentar sobreescribir con el stock de la cookie
    if ($mueble) {
        $cookieData = request()->cookie("mueble_{$id}");
        
        if ($cookieData) {
            $arr = json_decode($cookieData, true);
            
            // 3. Sobreescribir el stock del mock con el valor de la cookie
            if (isset($arr['stock'])) {
                $mueble['stock'] = $arr['stock'];
            }
        }
    }
    
    // 4. Devolver el resultado (con el stock actualizado si se encontró la cookie)
        return $mueble; 
    }

    public function show(Request $request)
    {
        $carrito = $this->getCarritoFromStorage($request); 
        $subtotal = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        $impuestos = $subtotal * 0.16;
        $total = $subtotal + $impuestos;

        return view('carrito.index', compact('carrito', 'subtotal', 'impuestos', 'total')); 
    }

    public function add(Request $request, string $muebleId)
    {
        $mueble = $this->getMuebleById($muebleId);
        if (!$mueble) { return back()->with('error', 'El mueble no existe.'); }

        $cantidadAAnadir = $request->input('cantidad', 1);
        $carrito = $this->getCarritoFromStorage($request); 
        $stockDisponible = $mueble['stock'];
        $cantidadActualEnCarrito = $carrito[$muebleId]['cantidad'] ?? 0;
        $nuevaCantidadTotalEnCarrito = $cantidadActualEnCarrito + $cantidadAAnadir;

        // Validación de Stock (R4.d)
        if ($nuevaCantidadTotalEnCarrito > $stockDisponible) {
            return back()->with('error', 'No hay suficiente stock. Stock disponible: ' . $stockDisponible);
        }

        // DISMINUIR STOCK EN LA COOKIE DEL MUEBLE
        $stockFinal = $stockDisponible - $cantidadAAnadir;
        $this->updateMuebleStockInCookie($muebleId, $stockFinal);

        // ... (resto de lógica de carrito, sin cambios) ...
        if (isset($carrito[$muebleId])) {
            $carrito[$muebleId]['cantidad'] = $nuevaCantidadTotalEnCarrito;
        } else {
            $carrito[$muebleId] = [
                'mueble_id' => $muebleId, 'nombre' => $mueble['nombre'], 'precio' => $mueble['precio'], 
                'cantidad' => $cantidadAAnadir, 'stock_disponible' => $stockDisponible,
            ];
        }

        $this->saveCarritoToStorage($carrito); 
        return redirect()->route('carrito.show')->with('success', $mueble['nombre'] . ' añadido al carrito.');
    }

    // ... (update, remove, clear methods remain the same as the final corrected versions) ...
    public function update(Request $request, string $muebleId)
    {
        $request->validate(['cantidad' => 'required|integer|min:1']);
        $cantidadNueva = $request->input('cantidad');
        $carrito = $this->getCarritoFromStorage($request); 

        if (isset($carrito[$muebleId])) {
            $stockDisponible = $carrito[$muebleId]['stock_disponible'];
            $cantidadVieja = $carrito[$muebleId]['cantidad'];
            
            if ($cantidadNueva > $stockDisponible) {
                return back()->with('error', 'No hay suficiente stock. Máximo: ' . $stockDisponible);
            }
            
            $diferenciaNeta = $cantidadNueva - $cantidadVieja; 
            $stockMuebleActual = $this->getMuebleById($muebleId)['stock'];
            $nuevoStockMueble = $stockMuebleActual - $diferenciaNeta;

            $this->updateMuebleStockInCookie($muebleId, $nuevoStockMueble); 

            $carrito[$muebleId]['cantidad'] = $cantidadNueva;
            $this->saveCarritoToStorage($carrito); 
            return back()->with('success', 'Cantidad actualizada.');
        }

        return back()->with('error', 'Mueble no encontrado en el carrito.');
    }

    public function remove(string $muebleId, Request $request)
    {
        $carrito = $this->getCarritoFromStorage($request); 

        if (isset($carrito[$muebleId])) {
            $cantidadDevuelta = $carrito[$muebleId]['cantidad'];
            
            $stockMuebleActual = $this->getMuebleById($muebleId)['stock'];
            $nuevoStockMueble = $stockMuebleActual + $cantidadDevuelta; 
            $this->updateMuebleStockInCookie($muebleId, $nuevoStockMueble);

            unset($carrito[$muebleId]);
            $this->saveCarritoToStorage($carrito); 
            return back()->with('success', 'Mueble eliminado del carrito.');
        }

        return back()->with('error', 'Mueble no encontrado.');
    }

    public function clear(Request $request)
    {
        $carrito = $this->getCarritoFromStorage($request); 

        foreach ($carrito as $muebleId => $item) {
             $cantidadDevuelta = $item['cantidad'];
             
             $muebleActual = $this->getMuebleById($muebleId); 
             if ($muebleActual) {
                 $stockMuebleActual = $muebleActual['stock'];
                 $nuevoStockMueble = $stockMuebleActual + $cantidadDevuelta;
                 $this->updateMuebleStockInCookie($muebleId, $nuevoStockMueble);
             }
        }

        $key = $this->getStorageIdentifier();

        Session::forget($key);
        Cookie::queue(Cookie::forget($key)); 
        return back()->with('success', 'Carrito vaciado correctamente.');
    }
}