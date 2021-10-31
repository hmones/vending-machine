<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->role === User::SELLER;
    }

    public function update(User $user, Product $product): bool
    {
        return $user->role === User::SELLER && $user->id === optional($product->seller)->id;
    }
}
