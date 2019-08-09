<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Resources\Permission\PermissionCollection;
use App\Http\Resources\Permission\PermissionResource;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new PermissionCollection(Permission::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'slug' => 'required|string|max:255|unique:permissions',
            'name' => 'required|string|max:255|unique:permissions',
        ]);
        
        $permission = Permission::create([
            'slug' => $request->get('slug'),
            'name' => $request->get('name'),
        ]);
        
        return new PermissionResource($permission);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(/*Permission $permission*/ int $id)
    {
        return new PermissionResource(Permission::FindOrFail($id));
        //return new PermissionResource($permission);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $permission->name = $request->get('name');
        $permission->save();
        
        return new PermissionResource($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->roles()->detach();
        $permission->delete();
        
        return new PermissionCollection(Permission::all());
        //return response()->json('successfully deleted');
    }
}
