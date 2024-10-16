<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    public function isAdmin(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Cart $cart): bool
    {
        return $user->id == $cart->user->id || $user->is_admin;
    }

    public function create(User $user): bool
    {
        return (bool)$user->id;
    }

    public function update(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user->id;
    }

    public function delete(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user->id;
    }

    public function restore(User $user, Cart $cart): bool
    {
        return $user->is_admin;
    }

    public function forceDelete(User $user, Cart $cart): bool
    {
        return $user->is_admin;
    }
}
