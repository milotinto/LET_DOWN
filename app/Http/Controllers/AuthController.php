<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login', 'register']]);
    }
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
    public function register(Request $request)
    {   

        $this->validate($request,[
            'nombre'=>'required|max:25',
            'telefono'=>'required|max:15',
            'email'=>'required|email|max:25|unique:users',
            'password'=>'required|max:20|confirmed'
        ]);
        $user = User::create([
            'nombre'=>$request->nombre,
            'telefono'=>$request->telefono,
            'email'=>$request->email,
            'password'=> Hash::make (trim($request->password))
        ]);
        return response()->json([
            'datos'=>$user,
            'proceso'=>'Proceso realizado con éxito'
        ]);
    }
    
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['Error:' => 'Token no permitido.'], 401);
        }

        return $this->respondWithToken($token);
        return response()->json([
            'Mensaje:' => 'Sesión iniciada correctamente.'
        ]);
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
        
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Cierre de sesión exitoso.']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    public function update(Request $request, $id)
    {
        
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
           
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        if ($user->id != $id) {
            return response()->json(['msg' => 'No puedes actualizar esa información.', 'user_id' => $user->id, 'id_url'=>$id], 403);
            
        }

        // Validar la solicitud y actualizar la información del usuario aquí
        $user = User::find($id);
        $old_user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->nombre = $request->nombre;
        $user->telefono = $request->telefono;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->password = Hash::make (trim($request->password));
        $user->save();

        if ($user->save()){
            return response()->json([
                'datos'=>$user,
                'mensaje:'=>'Proceso exitoso, datos actualizados correctamente.',
                'datos viejos'=>$old_user
            ]);
        }else{
            return response()->json([
                'proceso:'=>false,
                'mensaje:'=>'No se han actualizado los datos del usuario.'
            ]);
        }
    
        return response()->json([
            'error:'=>true,
            'mensaje:'=>'No existe el usuario.'
        ]);
    
        
    }



    public function delete($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException) {
            return response()->json([
                'msg' => 'Error, no puedes eliminar esa información.'
            ]);
        }

        if ($user->id != $id) {
            return response()->json(['msg' => 'No puedes eliminar esa información.', 'user_id' => $user->id, 'id_url' => $id], 403);
        }

        $user = User::find($id);
        if (isset($user)) {
            $respuesta = User::destroy($id);
            JWTAuth::invalidate(JWTAuth::getToken());
            if ($respuesta) {
                return response()->json([
                    'msg:' => 'Usuario eliminado correctamente.'
                ]);
            } else {
                return response()->json([
                    'msg:' => 'No se ha eliminado el usuario.'
                ]);
            }
        } else {
            return response()->json([
                'msg:' => 'No existe el estudiante.'
            ]);
        }
    }

}
