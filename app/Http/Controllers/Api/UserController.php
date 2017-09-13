<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    protected $repo;

    public function __construct(User $repo)
    {
        $this->repo = $repo;
    }

    public function store(UserRequest $request)
    {
        try {
            $user = $this->repo->saveUser($request->all());
            return response()->json(['status' => 'success', 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'data' => ['error' => $e->getMessage()]]);
        }
    }

    public function me()
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
