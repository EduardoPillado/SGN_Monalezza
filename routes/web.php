<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pedido_controller;
use App\Http\Controllers\Usuario_controller;
use App\Http\Controllers\Empleado_controller;
use App\Http\Controllers\Cliente_controller;
use App\Http\Controllers\Proveedor_controller;
use App\Http\Controllers\Producto_controller;
use App\Http\Controllers\Entradas_caja_controller;
use App\Http\Controllers\Reporte_controller;
use App\Http\Controllers\Corte_caja_controller;
use App\Http\Controllers\Inventario_controller;
use App\Http\Controllers\Ingrediente_controller;
use App\Http\Controllers\Reserva_controller;
use App\Http\Controllers\Asistencia_controller;
use App\Http\Controllers\Medio_pedido_controller;
use App\Http\Controllers\Nomina_controller;
use App\Http\Controllers\Servicio_controller;
use App\Http\Controllers\Tipo_gasto_controller;
use App\Http\Controllers\Tipo_pago_controller;
use App\Http\Controllers\Tipo_producto_controller;
use App\Http\Controllers\Detalle_efectivo_controller;
use App\Http\Controllers\Mesa_controller;

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
Route::get('/editarPedido/{pedido_pk}', [Pedido_controller::class, 'datosParaEdicion'])->name('pedido.datosParaEdicion');
Route::put('/editandoPedido/{pedido_pk}', [Pedido_controller::class, 'actualizar'])->name('pedido.actualizar');
Route::match(['get', 'put'], '/marcandoPendientePedido/{pedido_pk}', [Pedido_controller::class, 'pendiente'])->name('pedido.pendiente');
Route::match(['get', 'put'], '/marcandoEntregaPedido/{pedido_pk}', [Pedido_controller::class, 'entregado'])->name('pedido.entregado');
Route::match(['get', 'put'], '/marcandoCancelacionPedido/{pedido_pk}', [Pedido_controller::class, 'cancelado'])->name('pedido.cancelado');
Route::get('/ticket/{pedido_pk}', [Pedido_controller::class, 'mostrarTicket'])->name('ticket.mostrar');
Route::get('/ventasFiltradas', [Pedido_controller::class, 'filtrar'])->name('pedido.filtrar');

Route::post('/registrandoMedioPedido', [Medio_pedido_controller::class, 'insertar'])->name('medio_pedido.insertar');
Route::get('/editarMedioPedido/{medio_pedido_pk}', [Medio_pedido_controller::class, 'datosParaEdicion'])->name('medio_pedido.datosParaEdicion');
Route::put('/editandoMedioPedido/{medio_pedido_pk}', [Medio_pedido_controller::class, 'actualizar'])->name('medio_pedido.actualizar');
Route::match(['get', 'put'], '/dandoDeBajaMedioPedido/{medio_pedido_pk}', [Medio_pedido_controller::class, 'baja'])->name('medio_pedido.baja');
Route::match(['get', 'put'], '/dandoDeAltaMedioPedido/{medio_pedido_pk}', [Medio_pedido_controller::class, 'alta'])->name('medio_pedido.alta');

Route::post('/registrandoTipoPago', [Tipo_pago_controller::class, 'insertar'])->name('tipo_pago.insertar');
Route::get('/editarTipoPago/{tipo_pago_pk}', [Tipo_pago_controller::class, 'datosParaEdicion'])->name('tipo_pago.datosParaEdicion');
Route::put('/editandoTipoPago/{tipo_pago_pk}', [Tipo_pago_controller::class, 'actualizar'])->name('tipo_pago.actualizar');
Route::match(['get', 'put'], '/dandoDeBajaTipoPago/{tipo_pago_pk}', [Tipo_pago_controller::class, 'baja'])->name('tipo_pago.baja');
Route::match(['get', 'put'], '/dandoDeAltaTipoPago/{tipo_pago_pk}', [Tipo_pago_controller::class, 'alta'])->name('tipo_pago.alta');

Route::get('/producto/{producto_fk}/estado-stock', [Inventario_Controller::class, 'obtenerStockPorProducto'])->name('inventario.obtenerStockPorProducto');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Reportes de movimientos -----------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/generarReporteDeInventario', function () {
    $USUARIO_PK = session('usuario_pk');
    if ($USUARIO_PK) {
        return view('reporteInventario');
    } else {
        return redirect()->route('login')->with('warning', 'Inicia sesión antes');
    }
})->name('formReporte.inventario');
Route::post('/generandoReporteDeInventario', [Reporte_controller::class, 'generarReporteInventario'])->name('generarReporte.inventario');

Route::get('/generarReporteDeProducto', function () {
    $USUARIO_PK = session('usuario_pk');
    if ($USUARIO_PK) {
        return view('reporteProducto');
    } else {
        return redirect()->route('login')->with('warning', 'Inicia sesión antes');
    }
})->name('formReporte.producto');
Route::post('/generandoReporteDeProductos', [Reporte_controller::class, 'generarReporteProducto'])->name('generarReporte.producto');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Entradas de caja ------------------------------------------------------------------------------------------------------------------------------------------------------

Route::post('/registrandoEntradaDeCaja', [Entradas_caja_controller::class, 'insertar'])->name('entradas_caja.insertar');
Route::get('/entradasDeCaja', [Entradas_caja_controller::class, 'mostrar'])->name('entradas_caja.mostrar');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Corte de caja ---------------------------------------------------------------------------------------------------------------------------------------------------------

Route::post('/generandoCorte', [Corte_caja_controller::class, 'generarCorte'])->name('corteDeCaja.generarCorte');
Route::get('/cortes', [Corte_caja_controller::class, 'mostrar'])->name('corteDeCaja.mostrar');

Route::post('/registrandoEfectivo', [Detalle_efectivo_controller::class, 'insertar'])->name('efectivoInicial.insertar');

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

// Asistencia ------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/asistencias', [Asistencia_controller::class, 'mostrar'])->name('asistencia.mostrar');
Route::get('/asistencia/entrada', [Asistencia_controller::class, 'entrada'])->name('asistencia.entrada');
Route::post('/registrandoEntrada', [Asistencia_controller::class, 'registrarEntrada'])->name('asistencia.registrarEntrada');
Route::get('/asistencia/salida', [Asistencia_controller::class, 'salida'])->name('asistencia.salida');
Route::post('/registrandoSalida', [Asistencia_controller::class, 'registrarSalida'])->name('asistencia.registrarSalida');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Nómina ----------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/nomina', action: [Nomina_controller::class, 'mostrar'])->name('nomina.mostrar');
Route::post('/generandoNomina', [Nomina_controller::class, 'generarNomina'])->name('nomina.generar');
Route::get('/nominasFiltradas', [Nomina_controller::class, 'filtrar'])->name('nomina.filtrar');

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
Route::get('/productosFiltrados', [Producto_controller::class, 'filtrar'])->name('producto.filtrar');

Route::post('/registrandoTipoProd', [Tipo_producto_controller::class, 'insertar'])->name('tipo_producto.insertar');
Route::get('/editarTipoProducto/{tipo_producto_pk}', [Tipo_producto_controller::class, 'datosParaEdicion'])->name('tipo_producto.datosParaEdicion');
Route::put('/editandoTipoProducto/{tipo_producto_pk}', [Tipo_producto_controller::class, 'actualizar'])->name('tipo_producto.actualizar');
Route::match(['get', 'put'], '/dandoDeBajaTipoProducto/{tipo_producto_pk}', [Tipo_producto_controller::class, 'baja'])->name('tipo_producto.baja');
Route::match(['get', 'put'], '/dandoDeAltaTipoProducto/{tipo_producto_pk}', [Tipo_producto_controller::class, 'alta'])->name('tipo_producto.alta');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Inventario ------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/inventario', [Inventario_controller::class, 'mostrar'])->name('inventario.mostrar');
Route::post('/agregandoStock', [Inventario_controller::class, 'insertar'])->name('inventario.insertar');
Route::get('/actualizarStock/{inventario_pk}', [Inventario_controller::class, 'datosParaEdicion'])->name('inventario.datosParaEdicion');
Route::put('/actualizandoStock/{inventario_pk}', [Inventario_controller::class, 'actualizar'])->name('inventario.actualizar');
Route::get('/inventarioFiltrado', [Inventario_controller::class, 'filtrar'])->name('inventario.filtrar');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Ingrediente -----------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/ingredientes', [Ingrediente_controller::class, 'mostrar'])->name('ingrediente.mostrar');
Route::post('/registrandoIngrediente', [Ingrediente_controller::class, 'insertar'])->name('ingrediente.insertar');
Route::get('/editarIngrediente/{ingrediente_pk}', [Ingrediente_controller::class, 'datosParaEdicion'])->name('ingrediente.datosParaEdicion');
Route::put('/editandoIngrediente/{ingrediente_pk}', [Ingrediente_controller::class, 'actualizar'])->name('ingrediente.actualizar');
Route::match(['get', 'put'], '/dandoDeBajaIngrediente/{ingrediente_pk}', [Ingrediente_controller::class, 'baja'])->name('ingrediente.baja');
Route::match(['get', 'put'], '/dandoDeAltaIngrediente/{ingrediente_pk}', [Ingrediente_controller::class, 'alta'])->name('ingrediente.alta');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Reserva ------------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/reservas', [Reserva_controller::class, 'mostrar'], [Mesa_controller::class, 'mostrar'])->name('reserva.mostrar', 'mesa.mostrar');
Route::post('/registrandoReserva', [Reserva_controller::class, 'insertar'])->name('reserva.insertar');
Route::get('/editarReserva/{reserva_pk}', [Reserva_controller::class, 'datosParaEdicion'])->name('reserva.datosParaEdicion');
Route::put('/editandoReserva/{reserva_pk}', [Reserva_controller::class, 'actualizar'])->name('reserva.actualizar');
Route::match(['get', 'put'], '/marcandoPendienteReserva/{reserva_pk}', [Reserva_controller::class, 'pendiente'])->name('reserva.pendiente');
Route::match(['get', 'put'], '/marcandoAtendidaReserva/{reserva_pk}', [Reserva_controller::class, 'atendida'])->name('reserva.atendida');
Route::match(['get', 'put'], '/marcandoCancelacionReserva/{reserva_pk}', [Reserva_controller::class, 'cancelada'])->name('reserva.cancelada');
Route::get('/reservasFiltradas', [Reserva_controller::class, 'filtrar'])->name('reserva.filtrar');

Route::post('/registrandoMesa', [Mesa_controller::class, 'insertar'])->name('mesa.insertar');
Route::get('/editarMesa/{mesa_pk}', [Mesa_controller::class, 'datosParaEdicion'])->name('mesa.datosParaEdicion');
Route::put('/editandoMesa/{mesa_pk}', [Mesa_controller::class, 'actualizar'])->name('mesa.actualizar');
Route::get('/editarMesa/{mesa_pk}', [Mesa_controller::class, 'datosParaEdicion'])->name('mesa.datosParaEdicion');
Route::put('/editandoMesa/{mesa_pk}', [Mesa_controller::class, 'actualizar'])->name('mesa.actualizar');

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Gasto -----------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/gastos', [Servicio_controller::class, 'mostrar'], [Tipo_gasto_controller::class, 'mostrar'])->name('gasto.mostrar', 'tipo_gasto.mostrar');
Route::post('/registrandoGasto', [Servicio_controller::class, 'insertar'])->name('gasto.insertar');
Route::get('/editarGasto/{servicio_pk}', [Servicio_controller::class, 'datosParaEdicion'])->name('gasto.datosParaEdicion');
Route::put('/editandoGasto/{servicio_pk}', [Servicio_controller::class, 'actualizar'])->name('gasto.actualizar');

Route::post('/registrandoTipoGasto', [Tipo_gasto_controller::class, 'insertar'])->name('tipo_gasto.insertar');
Route::get('/editarTipoGasto/{tipo_gasto_pk}', [Tipo_Gasto_controller::class, 'datosParaEdicion'])->name('tipo_gasto.datosParaEdicion');
Route::put('/editandoTipoGasto/{tipo_gasto_pk}', [Tipo_Gasto_controller::class, 'actualizar'])->name('tipo_gasto.actualizar');
Route::match(['get', 'put'], '/dandoDeBajaTipoGasto/{tipo_gasto_pk}', [Tipo_gasto_controller::class, 'baja'])->name('tipo_gasto.baja');
Route::match(['get', 'put'], '/dandoDeAltaTipoGasto/{tipo_gasto_pk}', [Tipo_gasto_controller::class, 'alta'])->name('tipo_gasto.alta');

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
