<?php

namespace App\Models;

use JsonSerializable;
use Illuminate\Support\Facades\Request; // Necesario para acceder a request()->cookie

class Mueble implements JsonSerializable
{
    private $id;
    private $nombre;
    private $categoria_id = [];
    private $descripcion;
    private $precio;
    private $stock;
    private $materiales;
    private $dimensiones;
    private $color_principal;
    private $destacado;
    private $imagenes = [];

    public function __construct(
        $id, $nombre, $categoria_id = [], $descripcion = null, $precio = 0, $stock = 0,
        $materiales = null, $dimensiones = null, $color_principal = null, $destacado = false, $imagenes = []
    )
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->categoria_id = $categoria_id;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->materiales = $materiales;
        $this->dimensiones = $dimensiones;
        $this->color_principal = $color_principal;
        $this->destacado = $destacado;
        $this->imagenes = $imagenes;
    }

    // ... dentro de Mueble.php

    public static function getAllMockData(): array
    {
        // El modelo SOLO devuelve la lista de mocks base.
        return [
            // Mocks base existentes
            'MESA1' => ['id' => 'MESA1', 'nombre' => 'Mesa de Comedor Lusso', 'precio' => 250.00, 'stock' => 5],
            'SILLA3' => ['id' => 'SILLA3', 'nombre' => 'Silla Eames Clásica', 'precio' => 75.00, 'stock' => 0],
            'SOFA1' => ['id' => 'SOFA1', 'nombre' => 'Sofá Copenhagen 3 plazas', 'precio' => 499.00, 'stock' => 8],
            'MESA2' => ['id' => 'MESA2', 'nombre' => 'Mesa Auxiliar Nordic', 'precio' => 89.50, 'stock' => 12],
            'ARMARIO1' => ['id' => 'ARMARIO1', 'nombre' => 'Armario Roble Escandinavo', 'precio' => 699.00, 'stock' => 3],
            'ESTANTERIA1' => ['id' => 'ESTANTERIA1', 'nombre' => 'Estantería Modular Loft', 'precio' => 199.99, 'stock' => 10],
            'MESITA1' => ['id' => 'MESITA1', 'nombre' => 'Mesita de Noche Siena', 'precio' => 59.90, 'stock' => 15],
            'BANCO1' => ['id' => 'BANCO1', 'nombre' => 'Banco de Entrada Oslo', 'precio' => 129.00, 'stock' => 6],
            'ESCRITORIO1' => ['id' => 'ESCRITORIO1', 'nombre' => 'Escritorio Vintage', 'precio' => 249.00, 'stock' => 4],
            'COMODA1' => ['id' => 'COMODA1', 'nombre' => 'Cómoda Flora 4 cajones', 'precio' => 179.50, 'stock' => 7],
            'LAMPARA1' => ['id' => 'LAMPARA1', 'nombre' => 'Lámpara de Pie Arco', 'precio' => 89.00, 'stock' => 20],
            'SILLON1' => ['id' => 'SILLON1', 'nombre' => 'Sillón Relax Savona', 'precio' => 349.00, 'stock' => 2],
        ];
    }
// ...

    public function jsonSerialize(): array { return ['id' => $this->id, 'nombre'=> $this->nombre, 'categoria_id' => $this->categoria_id, 'descripcion' => $this->descripcion, 'precio' => $this->precio, 'stock' => $this->stock, 'materiales' => $this->materiales, 'dimensiones'=> $this->dimensiones, 'color_principal'=> $this->color_principal, 'destacado'=> $this->destacado, 'imagenes'=> $this->imagenes]; }
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; return $this; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function getCategoria() { return $this->categoria_id; }
    public function setCategoria($categoria_id) { $this->categoria_id = $categoria_id; return $this; }
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }
    public function getPrecio() { return $this->precio; }
    public function setPrecio($precio) { $this->precio = $precio; return $this; }
    public function getStock() { return $this->stock; }
    public function setStock($stock) { $this->stock = $stock; return $this; }
    public function getMateriales() { return $this->materiales; }
    public function setMateriales($materiales) { $this->materiales = $materiales; return $this; }
    public function getDimensiones() { return $this->dimensiones; }
    public function setDimensiones($dimensiones) { $this->dimensiones = $dimensiones; return $this; }
    public function getColorPrincipal() { return $this->color_principal; }
    public function setColorPrincipal($color_principal) { $this->color_principal = $color_principal; return $this; }
    public function getDestacado() { return $this->destacado; }
    public function setDestacado($destacado) { $this->destacado = $destacado; return $this; }
    public function getImagenes() { return $this->imagenes; }
    public function setImagenes($imagenes) { $this->imagenes = $imagenes; return $this; }
}