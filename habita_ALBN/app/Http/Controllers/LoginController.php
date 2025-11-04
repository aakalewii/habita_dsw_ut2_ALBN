<?php

namespace App\Http\Controllers;

use App\Enums\RolUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function mostrar()
    {
        // Mostrar login sin errores inicialmente
        return view('login', ['mensaje_error' => null]);
    }

    public function login(Request $request)
    {
        // Validación de campos
        $datos = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4',
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
        ]);

        // Verificar usuario
        $usuario = User::verificarUsuario($datos['email'], $datos['password']);

        if (!$usuario) {
            // Si no existe, mostramos mensaje de credenciales incorrectas
            return view('login', ['mensaje_error' => 'Credenciales incorrectas.']);
        }

        // Login correcto → guardar datos en sesión
        $datosSesion = [
            'email' => $usuario->email,
            'nombre'  => $usuario->nombre,
            'rol' => $usuario->rol->value,
            'fecha_ingreso' => now()->toString(),
        ];

        Session::put('usuario', json_encode($datosSesion));
        Session::put('autorizacion_usuario', true);
        Session::regenerate();

        // "Recordarme"
        if ($request->has('recuerdame')) {
            config(['session.lifetime' => 43200]); // 30 días
            config(['expire_on_close' => true]);
        }

        // Redirigir según rol
        return $usuario->rol === RolUser::ADMIN
            ? redirect()->route('dashboard')
            : redirect()->route('principal');
    }

    public function cerrarSesion()
    {
        Session::forget(['autorizacion_usuario','usuario']);
        Session::regenerate();

        return redirect()->route('login');
    }
}
