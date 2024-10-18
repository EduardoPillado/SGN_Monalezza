<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domicilio;
use App\Models\Telefono;
use App\Models\Cliente;
use Illuminate\Validation\Rule;

class Cliente_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'nombre_cliente' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:cliente,nombre_cliente'],
            'calle' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50'],
            'numero_externo' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/'],
            'numero_interno' => ['nullable', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'unique:domicilio,numero_interno'],
            'referencias' => ['nullable', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/'],
            'telefono' => ['required', 'regex:/^[0-9]{10,15}$/', 'unique:telefono,telefono'],
        ], [
            'nombre_cliente.required' => 'El nombre del cliente es obligatorio.',
            'nombre_cliente.regex' => 'El nombre del cliente solo puede contener letras, números y espacios.',
            'nombre_cliente.max' => 'El nombre del cliente no debe exceder los 50 caracteres.',
            'nombre_cliente.unique' => 'Este nombre de cliente ya está registrado.',
            
            'calle.required' => 'La calle es obligatoria.',
            'calle.regex' => 'La calle solo puede contener letras, números y espacios.',
            'calle.max' => 'La calle no debe exceder los 50 caracteres.',
            
            'numero_externo.required' => 'El número externo es obligatorio.',
            'numero_externo.regex' => 'El número externo solo puede contener letras, números y espacios.',
            
            'numero_interno.regex' => 'El número interno solo puede contener letras, números y espacios.',
            'numero_interno.unique' => 'El número interno ya está registrado.',
            
            'referencias.regex' => 'Las referencias solo pueden contener letras, números y espacios.',
            
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono debe contener entre 10 y 15 dígitos.',
            'telefono.unique' => 'Este teléfono ya está registrado.',
        ]);

        $domicilio=new Domicilio();

        $domicilio->calle=$req->calle;
        $domicilio->numero_externo=$req->numero_externo;
        $domicilio->numero_interno=$req->numero_interno;
        $domicilio->referencias=$req->referencias;
        $domicilio->save();

        $telefono=new Telefono();

        $telefono->telefono=$req->telefono;
        $telefono->save();

        $cliente=new Cliente();

        $cliente->nombre_cliente=$req->nombre_cliente;
        $cliente->domicilio_fk=$domicilio->domicilio_pk;
        $cliente->telefono_fk=$telefono->telefono_pk;
        $cliente->save();
        
        if ($cliente->cliente_pk) {
            return back()->with('success', 'Cliente registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosCliente = Cliente::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $rol = session('nombre_rol');
            if ($rol == 'Administrador') {
                return view('clientes', compact('datosCliente'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function datosParaEdicion($cliente_pk){
        $datosCliente = Cliente::findOrFail($cliente_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $rol = session('nombre_rol');
            if ($rol == 'Administrador') {
                return view('editarCliente', compact('datosCliente'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $cliente_pk){
        $datosCliente = Cliente::findOrFail($cliente_pk);
        $domicilio_pk = $datosCliente->domicilio->domicilio_pk;
        $telefono_pk = $datosCliente->telefono->telefono_pk;

        $req->validate([
            'nombre_cliente' => ['regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', Rule::unique('cliente', 'nombre_cliente')->ignore($cliente_pk, 'cliente_pk')], 
            'calle' => ['regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50'],
            'numero_externo' => ['regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/'],
            'numero_interno' => ['nullable', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', Rule::unique('domicilio', 'numero_interno')->ignore($domicilio_pk, 'domicilio_pk')],
            'referencias' => ['nullable', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/'],
            'telefono' => ['regex:/^[0-9]{10,15}$/', Rule::unique('telefono', 'telefono')->ignore($telefono_pk, 'telefono_pk')],
        ], [
            'nombre_cliente.regex' => 'El nombre del cliente solo puede contener letras, números y espacios.',
            'nombre_cliente.max' => 'El nombre del cliente no debe exceder los 50 caracteres.',
            'nombre_cliente.unique' => 'Este nombre de cliente ya está registrado.',
            
            'calle.regex' => 'La calle solo puede contener letras, números y espacios.',
            'calle.max' => 'La calle no debe exceder los 50 caracteres.',
            
            'numero_externo.regex' => 'El número externo solo puede contener letras, números y espacios.',
            
            'numero_interno.regex' => 'El número interno solo puede contener letras, números y espacios.',
            
            'referencias.regex' => 'Las referencias solo pueden contener letras, números y espacios.',
            
            'telefono.regex' => 'El teléfono debe contener entre 10 y 15 dígitos.',
            'telefono.unique' => 'Este teléfono ya está registrado.',
        ]);

        $datosCliente->domicilio->calle=$req->calle;
        $datosCliente->domicilio->numero_externo=$req->numero_externo;
        $datosCliente->domicilio->numero_interno=$req->numero_interno;
        $datosCliente->domicilio->referencias=$req->referencias;

        $datosCliente->telefono->telefono=$req->telefono;

        $datosCliente->nombre_cliente=$req->nombre_cliente;
        $datosCliente->domicilio_fk=$datosCliente->domicilio->domicilio_pk;
        $datosCliente->telefono_fk=$datosCliente->telefono->telefono_pk;

        $datosCliente->domicilio->save();
        $datosCliente->telefono->save();
        $datosCliente->save();
        
        if ($datosCliente->cliente_pk) {
            return redirect('/clientes')->with('success', 'Datos de cliente actualizados');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
