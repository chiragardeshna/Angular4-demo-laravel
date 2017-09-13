<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function attempt(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => 'fail', 'data' => ['error' => 'INVALID_CREDENTIALS']], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 'fail', 'data' => ['error' => 'UNABLE_TO_CREATE_TOKEN']], 500);
        }

        return response()->json(['status' => 'success', 'data' => ['token' => $token]]);
    }

    public function validateToken()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['status' => 'fail', 'data' => ['error' => 'TOKEN_EXPIRED']], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['status' => 'fail', 'data' => ['error' => 'TOKEN_INVALID']], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['status' => 'fail', 'data' => ['error' => 'TOKEN_ABSENT']], $e->getStatusCode());
        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(['status' => 'success', 'data' => ['user' => $user]]);
    }
}