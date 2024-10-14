<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAdmin(User $user): bool
    {
        return $user->is_admin;
    }

    public function viewUser(User $user): bool
    {
        return (bool)$user->id;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->id == $order->user->id || $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->id;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->id == $order->user->id || $user->is_admin;
    }

    public function cancel(User $user, Order $order): bool
    {
        return $user->id == $order->user->id;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->id || $user->is_admin;
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->is_admin;
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->is_admin;
    }
}
