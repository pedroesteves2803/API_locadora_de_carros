<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){

        $credenciais = $request->all(['email', 'password']);

        $token = auth('api')->attempt($credenciais);

        if($token){
            return response()->json(['token' => $token]);
        } else{
            return response()->json(['error' => 'Usuario inválido'], 403);
        }
    }

    public function logout(){
        auth('api')->logout();

        return response()->json(['msg' => 'Vc foi deslogado'], 200);
    }

    public function refresh(){
        $token = auth('api')->refresh();

        return response()->json(['token' => $token]);
    }

    public function me(){
        return response()->json(auth()->user());
    }
}
