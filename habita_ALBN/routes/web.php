<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\MuebleController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CarritoController;

Route::get(uri: '/', action: fn() => redirect()->route('dashboard'));

Route::get('/login', [LoginController::class, 'mostrar'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'cerrarSesion'])->name('logout');

Route::get('/dashboard', [AdministracionController::class, 'index'])->name('dashboard');
Route::get('/principal', [AdministracionController::class, 'principal'])->name('principal');

Route::get('/ver_sesion', function () {
    // Muestra todo el contenido de la sesión actual
    return Session::all();
});

Route::resource('categorias', CategoriaController::class);

// rutas del carritoController
Route::get('/carrito', [CarritoController::class, 'show'])->name('carrito.show');
Route::post('/carrito/insertar/{muebleId}', [CarritoController::class, 'add'])->name('carrito.add');
Route::post('/carrito/actualizar/{muebleId}', [CarritoController::class, 'update'])->name('carrito.update');
Route::post('/carrito/eliminar/{muebleId}', [CarritoController::class, 'remove'])->name('carrito.remove');
Route::post('/carrito/vaciar', [CarritoController::class, 'clear'])->name('carrito.clear');