<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LogoutAllController extends Controller
{
    public function store(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'You have been logged out of all active sessions']);
    }
}
