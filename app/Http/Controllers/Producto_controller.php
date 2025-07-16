<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Detalle_ingrediente;
use App\Models\Tipo_producto;

class Producto_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'nombre_producto' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:producto,nombre_producto'],
            'tipo_producto_fk' => ['required', 'exists:tipo_producto,tipo_producto_pk'],
            'precio_producto' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'imagen_producto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'ingredientes.*' => ['nullable', 'exists:ingrediente,ingrediente_pk'],
        ], [
            'nombre_producto.required' => 'El nombre del producto es obligatorio.',
            'nombre_producto.regex' => 'El nombre del producto solo puede contener letras, números y espacios.',
            'nombre_producto.max' => 'El nombre del producto no puede tener más de :max caracteres.',
            'nombre_producto.unique' => 'El nombre del producto ya existe.',

            'tipo_producto_fk.required' => 'El tipo de producto es obligatorio.',
            'tipo_producto_fk.exists' => 'El tipo de producto seleccionado no es válido.',

            'precio_producto.required' => 'El precio del producto es obligatorio.',
            'precio_producto.numeric' => 'El precio del producto debe ser un valor numérico.',
            'precio_producto.min' => 'El precio del producto debe ser mayor o igual a 0.01.',
            'precio_producto.max' => 'El precio del producto no debe exceder 999999.99.',

            'imagen_producto.image' => 'La imagen debe ser una imagen válida.',
            'imagen_producto.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'imagen_producto.max' => 'La imagen no debe exceder 3 MB.',

            'ingredientes.*.exists' => 'El ingrediente seleccionado no es válido.',
        ]);

        $producto=new Producto();

        $producto->nombre_producto=$req->nombre_producto;
        $producto->tipo_producto_fk=$req->tipo_producto_fk;
        $producto->precio_producto=$req->precio_producto;
        if ($req->hasFile('imagen_producto')) {
            $archivo = $req->file('imagen_producto');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('img/productos'), $nombreArchivo);
            $producto->imagen_producto = 'img/productos/' . $nombreArchivo;
        }
        $producto->estatus_producto=1;

        $producto->save();

        if ($req->has('ingredientes') && is_array($req->ingredientes) && count($req->ingredientes) > 0) {
            foreach ($req->ingredientes as $index => $ingrediente_pk) {
                if (!is_null($ingrediente_pk) && isset($req->cantidades_necesarias[$index])) {
                    $detalle = new Detalle_ingrediente();
                    $detalle->producto_fk = $producto->producto_pk;
                    $detalle->ingrediente_fk = $ingrediente_pk;
                    $detalle->cantidad_necesaria = $req->cantidades_necesarias[$index];
                    $detalle->save();
                }
            }
        }
        
        if ($producto->producto_pk) {
            return back()->with('success', 'Producto registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosProducto = Producto::all();
        $datosTipoProducto = Tipo_producto::where('estatus_tipo_producto', '=', 1)->get();
        $allTipoProducto = Tipo_producto::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('productos', compact('datosProducto', 'datosTipoProducto', 'allTipoProducto'));
        } else {
            return redirect('/login');
        }
    }

    public function filtrar(Request $req){
        // Por tipo de producto
        $query = Producto::with('tipo_producto');
        if ($req->filled('tipo_producto_fk')) {
            $query->where('tipo_producto_fk', $req->tipo_producto_fk);
        }

        // Por estatus de producto (inactivos, activos)
        $estatus = $req->input('estatus');
        if (in_array($estatus, ['0', '1'])) {
            $query->where('estatus_producto', $estatus);
        }

        $datosProducto = $query->get();
        $datosTipoProducto = Tipo_producto::where('estatus_tipo_producto', '=', 1)->get();
        $allTipoProducto = Tipo_producto::all();

        return view('productos', compact('datosProducto', 'datosTipoProducto', 'allTipoProducto'));
    }

    public function baja($producto_pk){
        $datosProducto = Producto::findOrFail($producto_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                if ($datosProducto) {

                    $datosProducto->estatus_producto = 0;
                    $datosProducto->save();

                    return back()->with('success', 'Producto dado de baja');
                } else {
                    return back()->with('error', 'Hay algún problema con la información');
                }
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function alta($producto_pk){
        $datosProducto = Producto::findOrFail($producto_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                if ($datosProducto) {

                    $datosProducto->estatus_producto = 1;
                    $datosProducto->save();

                    return back()->with('success', 'Producto dado de alta');
                } else {
                    return back()->with('error', 'Hay algún problema con la información');
                }
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function datosParaEdicion($producto_pk){
        $datosProducto = Producto::with('ingredientes')->findOrFail($producto_pk);

        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarProducto', compact('datosProducto'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $producto_pk){
        $datosProducto = Producto::findOrFail($producto_pk);

        $req->validate([
            'nombre_producto' => ['regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:producto,nombre_producto,' . $producto_pk . ',producto_pk'],
            'tipo_producto_fk' => ['exists:tipo_producto,tipo_producto_pk'],
            'precio_producto' => ['numeric', 'min:0.01', 'max:999999.99'],
            'imagen_producto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'ingredientes.*' => ['nullable', 'exists:ingrediente,ingrediente_pk'],
        ], [
            'nombre_producto.regex' => 'El nombre del producto solo puede contener letras, números y espacios.',
            'nombre_producto.max' => 'El nombre del producto no puede tener más de :max caracteres.',
            'nombre_producto.unique' => 'El nombre del producto ya existe.',

            'tipo_producto_fk.exists' => 'El tipo de producto seleccionado no es válido.',

            'precio_producto.numeric' => 'El precio del producto debe ser un valor numérico.',
            'precio_producto.min' => 'El precio del producto debe ser mayor o igual a 0.01.',
            'precio_producto.max' => 'El precio del producto no debe exceder 999999.99.',

            'imagen_producto.image' => 'La imagen debe ser una imagen válida.',
            'imagen_producto.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'imagen_producto.max' => 'La imagen no debe exceder 3 MB.',

            'ingredientes.*.exists' => 'El ingrediente seleccionado no es válido.',
        ]);

        $datosProducto->nombre_producto = $req->nombre_producto;
        $datosProducto->tipo_producto_fk = $req->tipo_producto_fk;
        $datosProducto->precio_producto = $req->precio_producto;
        if ($req->hasFile('imagen_producto')) {
            $archivo = $req->file('imagen_producto');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('img/productos'), $nombreArchivo);
            $datosProducto->imagen_producto = 'img/productos/' . $nombreArchivo;
        }
        $datosProducto->save();

        $ingredientes = $req->ingredientes ? array_filter($req->ingredientes) : [];
        
        Detalle_ingrediente::where('producto_fk', $producto_pk)->delete();

        foreach ($ingredientes as $index => $ingrediente_pk) {
            if (isset($req->cantidades_necesarias[$index])) {
                $detalle = new Detalle_ingrediente();
                $detalle->producto_fk = $producto_pk;
                $detalle->ingrediente_fk = $ingrediente_pk;
                $detalle->cantidad_necesaria = $req->cantidades_necesarias[$index];
                $detalle->save();
            }
        }
        
        if ($datosProducto->producto_pk) {
            return redirect('/productos')->with('success', 'Datos de producto actualizados');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
