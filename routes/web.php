<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pedido_controller;
use App\Http\Controllers\Usuario_controller;
use App\Http\Controllers\Empleado_controller;
use App\Http\Controllers\Cliente_controller;
use App\Http\Controllers\Proveedor_controller;
use App\Http\Controllers\Producto_controller;
use App\Http\Controllers\Corte_caja_controller;

Route::get('/', function () {
    $USUARIO_PK = session('usuario_pk');
    if ($USUARIO_PK) {
        return view('inicio');
    } else {
        return redirect()->route('login')->with('warning', 'Inicia sesión antes');
    }
})->name('inicio');

// Pedido ----------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/', [Pedido_controller::class, 'mostrarParaInsertar'])->name('pedido.mostrarParaInsertar');
Route::post('/registrandoPedido', [Pedido_controller::class, 'insertar'])->name('pedido.insertar');
Route::get('/ventas', [Pedido_controller::class, 'mostrar'])->name('pedido.mostrar');
Route::match(['get', 'put'], '/marcandoPendientePedido/{pedido_pk}', [Pedido_controller::class, 'pendiente'])->name('pedido.pendiente');
Route::match(['get', 'put'], '/marcandoEntregaPedido/{pedido_pk}', [Pedido_controller::class, 'entregado'])->name('pedido.entregado');
Route::match(['get', 'put'], '/marcandoCancelacionPedido/{pedido_pk}', [Pedido_controller::class, 'cancelado'])->name('pedido.cancelado');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Corte de caja ---------------------------------------------------------------------------------------------------------------------------------------------------------

Route::post('/generandoCorte', [Corte_caja_controller::class, 'generarCorte'])->name('corteDeCaja.generarCorte');
Route::get('/cortes', [Corte_caja_controller::class, 'mostrar'])->name('corteDeCaja.mostrar');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

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
Route::match(['get', 'put'], '/dandoDeBajaEmpleado/{empleado_pk}', [Empleado_controller::class, 'baja'])->name('empleado.baja');
Route::match(['get', 'put'], '/dandoDeAltaEmpleado/{empleado_pk}', [Empleado_controller::class, 'alta'])->name('empleado.alta');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Cliente ---------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/clientes', [Cliente_controller::class, 'mostrar'])->name('cliente.mostrar');
Route::post('/registrandoCliente', [Cliente_controller::class, 'insertar'])->name('cliente.insertar');
Route::get('/editarCliente/{cliente_pk}', [Cliente_controller::class, 'datosParaEdicion'])->name('cliente.datosParaEdicion');
Route::put('/editandoCliente/{cliente_pk}', [Cliente_controller::class, 'actualizar'])->name('cliente.actualizar');
Route::match(['get', 'put'], '/dandoDeBajaCliente/{cliente_pk}', [Cliente_controller::class, 'baja'])->name('cliente.baja');
Route::match(['get', 'put'], '/dandoDeAltaCliente/{cliente_pk}', [Cliente_controller::class, 'alta'])->name('cliente.alta');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Proveedor -------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/proveedores', [Proveedor_controller::class, 'mostrar'])->name('proveedor.mostrar');
Route::post('/registrandoProveedor', [Proveedor_controller::class, 'insertar'])->name('proveedor.insertar');
Route::get('/editarProveedor/{proveedor_pk}', [Proveedor_controller::class, 'datosParaEdicion'])->name('proveedor.datosParaEdicion');
Route::put('/editandoProveedor/{proveedor_pk}', [Proveedor_controller::class, 'actualizar'])->name('proveedor.actualizar');
Route::match(['get', 'put'], '/dandoDeBajaProveedor/{proveedor_pk}', [Proveedor_controller::class, 'baja'])->name('proveedor.baja');
Route::match(['get', 'put'], '/dandoDeAltaProveedor/{proveedor_pk}', [Proveedor_controller::class, 'alta'])->name('proveedor.alta');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Producto --------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/productos', [Producto_controller::class, 'mostrar'])->name('producto.mostrar');
Route::post('/registrandoProducto', [Producto_controller::class, 'insertar'])->name('producto.insertar');
Route::get('/editarProducto/{producto_pk}', [Producto_controller::class, 'datosParaEdicion'])->name('producto.datosParaEdicion');
Route::put('/editandoProducto/{producto_pk}', [Producto_controller::class, 'actualizar'])->name('producto.actualizar');
Route::match(['get', 'put'], '/dandoDeBajaProducto/{producto_pk}', [Producto_controller::class, 'baja'])->name('producto.baja');
Route::match(['get', 'put'], '/dandoDeAltaProducto/{producto_pk}', [Producto_controller::class, 'alta'])->name('producto.alta');

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
