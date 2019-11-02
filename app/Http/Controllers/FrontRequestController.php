<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontRequestController extends Controller
{
    public function getDeadlines()
    {
        $deadlineNames = config('workflow.deadline_names');
        
        return response()->json(['data' => $deadlineNames]);
    }
    
    public function getRoleNames()
    {
        $roleNames = config('workflow.role_names');
        
        return response()->json(['data' => $roleNames]);
    }
    
    public function getPermissionNames()
    {
        $permissionNames = config('workflow.permission_names');
        
        return response()->json(['data' => $permissionNames]);
    }
    
    public function getRouteValues()
    {
        $routeValues = config('workflow.route_values');
        
        return response()->json(['data' => $routeValues]);
    }
    
    public function getProcessValues()
    {
        $processValues = config('workflow.process_values');
        
        return response()->json(['data' => $processValues]);
    }
}
