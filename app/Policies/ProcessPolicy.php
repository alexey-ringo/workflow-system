<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcessPolicy
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
        return $user->canDo('process_index');
    }
    
    public function store(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('process_store');
    }
    
    public function show(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('process_show');
    }
    
    public function update(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('process_update');
    }
    
    public function destroy(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('process_destroy');
    }
}
