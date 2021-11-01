<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
    public function store(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'You have been logged out of your current session, your token is no longer valid']);
    }
}
