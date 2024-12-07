<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function isAdmin(User $user)
    {
        return $user->is_admin;
    }


    //    public function update(User $user, Product $product): Response
    //    {
    //        return $user->role=='admin'
    //            ? Response::allow()
    //            : Response::denyWithStatus(403);
    //    }
    //
    //
    //    public function destroy(User $user, Product $product): Response
    //    {
    //        return $user->role=='admin'
    //            ? Response::allow()
    //            : Response::denyWithStatus(403);
    //    }
    //
    //
    //    public function restore(User $user, Product $product): Response
    //    {
    //        return $user->role=='admin'
    //            ? Response::allow()
    //            : Response::denyWithStatus(403);
    //    }
    //
    //
    //    public function forceDelete(User $user, Product $product): Response
    //    {
    //        return $user->role=='admin'
    //            ? Response::allow()
    //            : Response::denyWithStatus(403);
    //    }
}
