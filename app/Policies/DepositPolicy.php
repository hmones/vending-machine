<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepositPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->role === User::BUYER;
    }

    public function delete(User $user): bool
    {
        return $user->role === User::BUYER;
    }
}
