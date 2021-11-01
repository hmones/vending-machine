<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();
        $isLogged = auth()->user()->tokens()->count() > 0;
        $token = auth()->user()->createToken(auth()->user()->role)->plainTextToken;

        return response()->json([
            'message' => $isLogged ? 'There is already an active session using your account' : 'Success',
            'token'   => $token,
        ], JsonResponse::HTTP_CREATED);
    }
}
