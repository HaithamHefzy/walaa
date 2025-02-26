<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Handle user login and return JWT token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->errorResponse('Invalid credentials!', 401);
        }

        $user = auth()->user();

        activity()
            ->causedBy($user)
            ->withProperties([
                'username' => $user->name,
            ])
            ->log('تسجيل دخول للسيستم');
        return $this->respondWithToken($token, $user, 'Login successful');
    }

    /**
     * Handle user logout.
     */
    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->successResponse(null, 'Successfully logged out');
    }

    /**
     * Return JWT token with user details.
     */
    protected function respondWithToken($token, $user, $message = 'Token generated successfully'): JsonResponse
    {
        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 604800,
            'user' => new \App\Http\Resources\UserResource($user),
        ], $message);
    }
}
