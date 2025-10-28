<?php

namespace App\Models;

use JsonSerializable;

class Categoria implements JsonSerializable
{
    public string $id;
    public string $nombre;
    public ?string $descripcion;

    public function __construct(string $id, string $nombre, ?string $descripcion = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ];
    }
}

