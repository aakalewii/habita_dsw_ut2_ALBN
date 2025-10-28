<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarritoController extends Controller
{
    /**
     * Genera la clave del carrito única asociada al ID de la sesión actual.
     * Esto asegura que el carrito persista por sesión.
     */
    private function getCarritoKey(): string
    {
        // Usa el ID de la sesión actual para asociar el carrito
        return 'carrito_' . Session::getId();
    }
    
    /**
     * Función auxiliar para obtener datos de un mueble (Mock Data).
     * Requerido porque no se usa BD
     */
    private function getMuebleById(string $id): ?array
    {
        // Mock data con stock para validación
        $mueblesMock = [
            'MESA1' => [
                'id' => 'MESA1', 
                'nombre' => 'Mesa de Comedor Lusso', 
                'precio' => 250.00, 
                'stock' => 5,
                'categoria_id' => 'CAT1'
            ],
            'SOFA2' => [
                'id' => 'SOFA2', 
                'nombre' => 'Sofá Modular Confort', 
                'precio' => 850.00, 
                'stock' => 12,
                'categoria_id' => 'CAT2'
            ],
            'SILLA3' => [
                'id' => 'SILLA3', 
                'nombre' => 'Silla Eames Clásica', 
                'precio' => 75.00, 
                'stock' => 0, // Stock agotado para probar validación
                'categoria_id' => 'CAT3'
            ],
        ];
        
        return $mueblesMock[$id] ?? null;
    }


    /**
     * Mostramos el resumen del carrito con subtotales, impuestos y total.
     */
    public function show()
    {
        $carrito = Session::get($this->getCarritoKey(), []);
        
        // CÁLCULOS (Simulados)
        $subtotal = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        $impuestos = $subtotal * 0.16; // 16% de impuestos simulados
        $total = $subtotal + $impuestos;

        return view('carrito.show', compact('carrito', 'subtotal', 'impuestos', 'total'));
    }

    /**
     * Añade un mueble al carrito o actualiza su cantidad.
     */
    public function add(Request $request, string $muebleId)
    {
        $mueble = $this->getMuebleById($muebleId);
        if (!$mueble) {
            return back()->with('error', 'El mueble no existe.');
        }

        $cantidadAAnadir = $request->input('cantidad', 1);

        $carrito = Session::get($this->getCarritoKey(), []);
        $stockDisponible = $mueble['stock'];
        $cantidadActual = $carrito[$muebleId]['cantidad'] ?? 0;
        $nuevaCantidad = $cantidadActual + $cantidadAAnadir;

        // Validación de stock
        if ($stockDisponible === 0) {
            return back()->with('error', 'Producto agotado. No se puede añadir al carrito.');
        }

        if ($nuevaCantidad > $stockDisponible) {
            return back()->with('error', 'No hay suficiente stock. Stock disponible: ' . $stockDisponible);
        }
        
        // Si el mueble ya está, actualiza la cantidad
        if (isset($carrito[$muebleId])) {
            $carrito[$muebleId]['cantidad'] = $nuevaCantidad;
        } else {
            // Si es nuevo, añade el ítem al carrito
            $carrito[$muebleId] = [
                'mueble_id' => $muebleId,
                'nombre' => $mueble['nombre'],
                'precio' => $mueble['precio'],
                'cantidad' => $cantidadAAnadir,
                'stock_disponible' => $stockDisponible, // Guardamos stock para validaciones
            ];
        }

        Session::put($this->getCarritoKey(), $carrito);
        // Usar mensaje flash para el mensaje de éxito
        return redirect()->route('carrito.show')->with('success', $mueble['nombre'] . ' añadido al carrito.');
    }

    /**
     * Actualizamos la cantidad de un mueble en el carrito.
     */
    public function update(Request $request, string $muebleId)
    {
        $request->validate(['cantidad' => 'required|integer|min:1']);
        $cantidad = $request->input('cantidad');
        $carrito = Session::get($this->getCarritoKey(), []);

        if (isset($carrito[$muebleId])) {
            $stockDisponible = $carrito[$muebleId]['stock_disponible'];

            // Validación de stock al actualizar
            if ($cantidad > $stockDisponible) {
                return back()->with('error', 'No hay suficiente stock. Máximo permitido: ' . $stockDisponible);
            }

            $carrito[$muebleId]['cantidad'] = $cantidad;
            Session::put($this->getCarritoKey(), $carrito);
            return back()->with('success', 'Cantidad actualizada correctamente.');
        }

        return back()->with('error', 'Mueble no encontrado en el carrito.');
    }

    /**
     * Elimina un ítem del carrito.
     */
    public function remove(string $muebleId)
    {
        $carrito = Session::get($this->getCarritoKey(), []);

        if (isset($carrito[$muebleId])) {
            $nombreMueble = $carrito[$muebleId]['nombre'];
            unset($carrito[$muebleId]);
            Session::put($this->getCarritoKey(), $carrito);
            return back()->with('success', 'Mueble "' . $nombreMueble . '" eliminado del carrito.');
        }

        return back()->with('error', 'Mueble no encontrado.');
    }

    /**
     * Vacía completamente el carrito.
     */
    public function clear()
    {
        Session::forget($this->getCarritoKey());
        return back()->with('success', 'Carrito vaciado correctamente.');
    }
}