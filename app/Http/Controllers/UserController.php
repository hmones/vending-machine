<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStore;
use App\Http\Requests\UserUpdate;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:update,user')->only(['update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        return response()->json(UserResource::collection(User::limit(100)->latest()->get()));
    }

    public function store(UserStore $request):JsonResponse
    {
        $user = User::create($request->safe()->toArray());

        return response()->json(new UserResource($user), Response::HTTP_CREATED);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json(new UserResource($user));
    }

    public function update(User $user, UserUpdate $request): JsonResponse
    {
        $user->update($request->safe()->toArray());

        return response()->json(new UserResource($user));
    }

    public function destroy(User $user): JsonResponse
    {
        return response()->json($user->delete(), Response::HTTP_NO_CONTENT);
    }
}
