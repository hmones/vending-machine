<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositStore;
use App\Http\Resources\UserResource;
use App\Models\Deposit;
use Illuminate\Http\JsonResponse;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:delete,App\Model\Deposit')->only('destroy');
        $this->middleware('can:create,App\Model\Deposit')->only('store');
    }

    public function store(DepositStore $request): JsonResponse
    {
        $user = auth()->user();
        $deposit = Deposit::create($request->safe()->toArray() + ['user_id' => $user->id]);
        $user->update(['deposit' => ($user->deposit + $request->amount)]);

        return response()->json($deposit->toArray(), JsonResponse::HTTP_CREATED);
    }

    public function destroy(): JsonResponse
    {
        Deposit::where('user_id', auth()->id())->delete();
        auth()->user()->update(['deposit' => 0]);

        return response()->json(new UserResource(auth()->user()));
    }
}
