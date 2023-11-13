<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

use Spatie\Permission\Models\Role;
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

        return response()->json([
            'status' => 'success',
            'message' => 'Felhasználó regisztrálva jogosulságok nélkül!',
            'user' => $user,
        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);
        $user = auth('api')->attempt($credentials);

        if (!$user) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Sikertelen bejelentkezés!',
            ]);
        }


        // --------------Spatie Role jogosultság ellenőrzés------------

        $user = auth('api')->user();

        if (!$user->hasRole('adminTest')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sikeres bejelentkezés!',
            ]);
        }

        $token = auth('api')->attempt($credentials);

        // token lejáratának időpntja dátum formátumban
        $decodedToken = JWTAuth::setToken($token)->getPayload();
        $expTimestamp = $decodedToken['exp'];
        $expirationDate = Carbon::createFromTimestamp($expTimestamp)->toDateTimeString();

        return response()->json([
            'status' => 'success',
            'message' => 'Sikeres bejelentkezés adminTest jogosultsággal!',
            'token' => $token,
            'token_type' => 'Bearer',
            'expire' => $expirationDate,
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


    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Sikeres kijelentkezés',
        ]);
    }

}
