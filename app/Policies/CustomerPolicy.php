<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function index(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('customer_index');
    }
    
    public function store(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('customer_store');
    }
    
    public function show(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('customer_show');
    }
    
    public function update(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('customer_update');
    }
    
    public function find(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('customer_find');
    }
}
