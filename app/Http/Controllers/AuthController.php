<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credenciais = $request->all(['email', 'password']);
        $token = auth('api')->attempt($credenciais);

        if ($token) {
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }


    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token], 200);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
