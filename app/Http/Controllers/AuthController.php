<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use illuminate\Support\Facades\Hash;
use illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
		$credentials = $request->only('username', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['message' => 'Invalid username and password']);
			}
		} catch(JWTException $e){
			return response()->json(['message' => 'Generate Token Failed']);
		}

		$user = JWTAuth::user();
		
		$outlet = DB::table('outlet')->where('id_outlet', $user->id_outlet)->first();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
			'user' => $user,
			'outlet' => $outlet
        ]);
	}

    public function loginCheck()
    {
        try {
            if(! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'Invalid Token']);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'Token Expired']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Invalid Token']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token absent']);
        }

        return response()->json(['message' => 'Authentication Success!']);
    }

   
    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json(['message' => 'Anda sudah logout']);
        } else {
            return response()->json(['message' => 'Gagal logout']);
        }
    }

}
