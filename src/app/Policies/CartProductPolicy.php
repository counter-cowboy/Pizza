<?php

namespace App\Policies;

use App\Models\CartProduct;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, CartProduct $cartItem): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, CartProduct $cartItem): bool
    {
    }

    public function delete(User $user, CartProduct $cartItem): bool
    {
    }

    public function restore(User $user, CartProduct $cartItem): bool
    {
    }

    public function forceDelete(User $user, CartProduct $cartItem): bool
    {
    }
}
