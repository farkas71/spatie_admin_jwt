<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class AuthController extends Controller
{
    // itt is megadható így az összes metódusra érvényes lesz, kivéve a register() és login()-ra
    // vagy route utvonalnál...
    // public function __construct()
    // {
    //     $this->middleware('auth:api', [
    //         'except' => [
    //             'login',
    //             'register',
    //         ]
    //     ]);
    // }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Felhasználó regisztrálva',
            'user' => $user,
            'token' => $token,
        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bejelentkezés sikertelen!'
            ]);
        }

        // token lejáratának időpntja dátum formátumban
        $decodedToken = JWTAuth::setToken($token)->getPayload();
        $expTimestamp = $decodedToken['exp'];
        $expirationDate = Carbon::createFromTimestamp($expTimestamp)->toDateTimeString();

        return response()->json([
            'status' => 'success',
            'message' => 'Sikeres bejelentkezés',
            'token' => $token,
            'token_type' => 'Bearer',
            'expire' => $expirationDate,
        ]);
    }

    
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Sikeres kijelentkezés',
        ]);
    }

    public function me(Request $request)
    {
        $token = $request->bearerToken(); 

        return response()->json([
            auth()->user(),
            'token' => $token,
        ]);
    }
}
