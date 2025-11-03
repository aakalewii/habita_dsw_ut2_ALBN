<?php

namespace App\Http\Controllers;

use App\Enums\RolUser;
// use App\Enums\RolUsuario; // Eliminado si no se usa
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
// use App\Models\Usuario; // Eliminado si no se usa

class LoginController extends Controller
{
    public function mostrar()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        // Verificar usuario
        // Asumiendo que User::verificarUsuario ha sido adaptado para devolver el objeto User
        $usuario = User::verificarUsuario($datos['email'], $datos['password']);

        if (!$usuario) {
            return back()->withErrors(['errorCredenciales' => 'Credenciales incorrectas.']);
        }

        $datosSesion = [
            'email' => $usuario->email,
            'nombre'  => $usuario->nombre,
            'rol' => $usuario->rol, // AÑADIDO: NECESARIO PARA LA REDIRECCIÓN Y PROTECCIÓN
            'fecha_ingreso' => now()->toDateTimeString(), // Formato corregido
        ];

        // Guardar usuario en sesión
        Session::put('usuario', json_encode($datosSesion));
        Session::put('autorizacion_usuario', true);
        Session::regenerate();

        // Si el usuario marcó "Recordarme"
        if ($request->has('recuerdame')) {
            // Aumentar manualmente la duración de la cookie de sesión
            config(['session.lifetime' => 43200]); // 30 días
            // Evitar que la sesión se elimine al cerrar el navegador
            config(['expire_on_close' => true]);
        }

        // Redirección basada en Rol
        // Asumiendo que RolUser::ADMIN está definido como una constante/enum string que se compara con $usuario->rol
        if ($usuario->rol === RolUser::ADMIN) { 
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('principal');
        }
    }

    public function cerrarSesion()
    {
        Session::flush();            // eliminar todos los datos de sesión
        Session::regenerate();       // regenerar ID de sesión por seguridad

        return redirect()->route('login')->with('mensaje', 'Sesión cerrada correctamente.');
    }
}