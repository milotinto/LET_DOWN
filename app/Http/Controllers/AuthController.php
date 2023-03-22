<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $inputs = $request-> input();
        $inputs ["password"] = Hash::make (trim($request->password));
        if ($respuesta = User::create($inputs)){
        return response()->json([
            'datos'=>$respuesta,
            'mensaje:'=>'Proceso exitoso, usuario registrado correctamente.'
        ]);
    }else{
        return response()->json([
            'Error:'=>true,
            'mensaje:'=>'Error, el usuario no ha sido registrado correctamente.'
        ]);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $exists = User::find($id);
        if(isset($exists)){
            return response()->json([
            'datos'=>$exists,
            'mensaje:'=>'Proceso exitoso, usuario encontrado correctamente.'
            ]);
        }else{
            return response()->json([
                'error:'=>true,
                'mensaje:'=>'Proceso fallido, no se ha encontrado este usuario.'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exists = User::find($id);

        if(isset($exists)){
            $exists->nombre = $request->nombre;
            $exists->telefono = $request->telefono;
            $exists->correo = $request->correo;
            $exists->password = Hash::make ($request->password);
            if ($exists->save()){
                return response()->json([
                    'datos'=>$exists,
                    'mensaje:'=>'Proceso exitoso, datos actualizados correctamente.'
                ]);
            }else{
                return response()->json([
                    'proceso:'=>false,
                    'mensaje:'=>'No se han actualizado los datos del usuario.'
                ]);
            }
        }else{
            return response()->json([
                'error:'=>true,
                'mensaje:'=>'No existe el usuario.'
            ]);
        }
    }


    /*
    DESACTIVAR UN USUARIO
    */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exists= User::find($id);
        if(isset($exists)){
            $resp = User::destroy($id);
            if($resp){
                return response()->json([
                    'datos'=> $exists,
                    'mensaje:'=> 'Usuario eliminado correctamente.'
                    ]);
                
            }else{
                return response()->json([
                    'mensaje:'=> 'No se ha eliminado el usuario o no existe.'
                ]);
            }
        }else{
            return response()->json([
                'error:' => 'Error, no existe el usuario #'.$id
            ]);
        }
    }
}
