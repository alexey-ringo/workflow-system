<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use Gate;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //public function __construct()
    //{
    //    $this->middleware('auth');
    //}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('layouts.app');
    }
    
    public function loggedUser(Request $request) {
        return response()->json(['data' => $request->user('api')]);
    }
    
    public function permissionsMenu() {
        
        $permissionsMenu = [];
        
        if(Gate::allows('index', Route::class)) {
            $permissionsMenu['route_index'] = 1;
        }
        if(Gate::allows('store', Route::class)) {
            $permissionsMenu['route_store'] = 1;
        }
        
        if(Gate::allows('index', Process::class)) {
            $permissionsMenu['process_index'] = 1;
        }
        if(Gate::allows('store', Process::class)) {
            $permissionsMenu['process_store'] = 1;
        }
        
        if(Gate::allows('index', Group::class)) {
            $permissionsMenu['group_index'] = 1;
        }
        if(Gate::allows('store', Group::class)) {
            $permissionsMenu['group_store'] = 1;
        }
        
        if(Gate::allows('index', Customer::class)) {
            $permissionsMenu['customer_index'] = 1;
        }
        if(Gate::allows('store', Customer::class)) {
            $permissionsMenu['customer_store'] = 1;
        }
        
        if(Gate::allows('find', Customer::class)) {
            $permissionsMenu['customer_find'] = 1;
        }
        
        if(Gate::allows('index', Contract::class)) {
            $permissionsMenu['contract_index'] = 1;
        }
        if(Gate::allows('store', Contract::class)) {
            $permissionsMenu['contract_store'] = 1;
        }
        
        if(Gate::allows('index', Task::class)) {
            $permissionsMenu['task_index'] = 1;
        }
        
        if(Gate::allows('index', Tariff::class)) {
            $permissionsMenu['tariff_index'] = 1;
        }
        if(Gate::allows('store', Tariff::class)) {
            $permissionsMenu['tariff_store'] = 1;
        }
        
        if(Gate::allows('index', Role::class)) {
            $permissionsMenu['role_index'] = 1;
        }
        if(Gate::allows('store', Role::class)) {
            $permissionsMenu['role_store'] = 1;
        }
        
        if(Gate::allows('index', Permission::class)) {
            $permissionsMenu['permission_index'] = 1;
        }
        if(Gate::allows('store', Permission::class)) {
            $permissionsMenu['permission_store'] = 1;
        }
        
        if(Gate::allows('index', User::class)) {
            $permissionsMenu['user_index'] = 1;
        }
        if(Gate::allows('store', User::class)) {
            $permissionsMenu['user_store'] = 1;
        }
        
        if(Gate::allows('index', User::class)) {
            $permissionsMenu['telegram_view'] = 1;
        }
        if(Gate::allows('store', User::class)) {
            $permissionsMenu['telegram_edit'] = 1;
        }
        
        return response()->json(['data' => $permissionsMenu]);
    }
}
