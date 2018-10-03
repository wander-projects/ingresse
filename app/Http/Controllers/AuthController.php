<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required'
        ]);

        try {
            $token = \Auth::guard('api')->attempt($credentials);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'invalid_credentials',
                    'errors' => $validator->errors()->all()
                ], 422);
            }

            if (!$token) {
                return response()->json([
                    'message' => 'invalid_credentials'
                ], 401);
            }
        }
        catch (JWTException $e) {
            return response()->json([
                'error' => 'could_not_create_token'],
                500);
        }

        return ['token' => $token];
    }
}
