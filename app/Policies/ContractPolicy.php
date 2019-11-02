<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContractPolicy
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
        return $user->canDo('contract_index');
    }
    
    public function store(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('contract_store');
    }
    
    public function show(User $user) {
        if($user->is_admin) {
            return true;
        }
        return $user->canDo('contract_show');
    }
}
