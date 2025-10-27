<?php

namespace App\Enums;

enum RolUser: string
{
    case ADMIN = 'admin';
    case USUARIO = 'usuario';

    public function descripcion(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador del sistema',
            self::USUARIO => 'Usuario estándar',
        };
    }
}