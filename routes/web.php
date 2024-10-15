<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usuario_controller;
use App\Http\Controllers\Empleado_controller;
use App\Http\Controllers\Cliente_controller;

Route::get('/', function () {
    $USUARIO_PK = session('usuario_pk');
    if ($USUARIO_PK) {
        return view('inicio');
    } else {
        return redirect()->route('login')->with('warning', 'Inicia sesión antes');
    }
})->name('inicio');

// Usuario ---------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/login', function () {
    $USUARIO_PK = session('usuario_pk');
    if ($USUARIO_PK) {
        return back()->with('warning', 'Ya has iniciado sesión');
    } else {
        return view('login');
    }
})->name('login');

Route::post('/iniciandoSesión', [Usuario_controller::class, 'login'])->name('usuario.login');
Route::get('/cerrandoSesión', [Usuario_controller::class, 'logout'])->name('usuario.logout');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Empleado --------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/empleados', [Empleado_controller::class, 'mostrar'])->name('empleado.mostrar');
Route::post('/registrandoEmpleado', [Empleado_controller::class, 'insertar'])->name('empleado.insertar');
Route::get('/editarEmpleado/{empleado_pk}', [Empleado_controller::class, 'datosParaEdicion'])->name('empleado.datosParaEdicion');
Route::put('/editandoEmpleado/{empleado_pk}', [Empleado_controller::class, 'actualizar'])->name('empleado.actualizar');
Route::match(['get', 'put'], '/eliminandoEmpleado/{empleado_pk}', [Empleado_controller::class, 'baja'])->name('empleado.baja');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Cliente ---------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/clientes', [Cliente_controller::class, 'mostrar'])->name('cliente.mostrar');
Route::post('/registrandoCliente', [Cliente_controller::class, 'insertar'])->name('cliente.insertar');
Route::get('/editarCliente/{cliente_pk}', [Cliente_controller::class, 'datosParaEdicion'])->name('cliente.datosParaEdicion');
Route::put('/editandoCliente/{cliente_pk}', [Cliente_controller::class, 'actualizar'])->name('cliente.actualizar');
Route::match(['get', 'put'], '/eliminandoCliente/{cliente_pk}', [Cliente_controller::class, 'baja'])->name('cliente.baja');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// require __DIR__.'/auth.php';

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
