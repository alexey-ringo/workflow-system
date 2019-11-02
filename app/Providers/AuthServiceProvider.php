<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Laravel\Passport\Passport;
use App\Models\Route;
use App\Models\Process;
use App\Models\Group;
use App\Models\User;
use App\Models\Customer;
use App\Models\Contract;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Tariff;
use App\Models\Role;
use App\Models\Permission;
use App\Models\TelegramSetting;

use App\Policies\RoutePolicy;
use App\Policies\ProcessPolicy;
use App\Policies\GroupPolicy;
use App\Policies\UserPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\ContractPolicy;
use App\Policies\TaskPolicy;
use App\Policies\CommentPolicy;
use App\Policies\TariffPolicy;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\TelegramSettingPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Route::class => RoutePolicy::class,
        Process::class => ProcessPolicy::class,
        Group::class => GroupPolicy::class,
        User::class => UserPolicy::class,
        Customer::class => CustomerPolicy::class,
        Contract::class => ContractPolicy::class,
        Task::class => TaskPolicy::class,
        Comment::class => CommentPolicy::class,
        Tariff::class => TariffPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        TelegramSetting::class => TelegramSettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Passport::routes();

        //
    }
}
