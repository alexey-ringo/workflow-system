<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleRelationResource;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return new RoleCollection(Role::all());
        return RoleResource::collection(Role::with('permissions')->get());
        //return RoleResource::collection(Role::all());
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
        //$user = User::find($request->user()->id);
        
        $validator = $request->validate([
            'slug' => 'required|string|max:255|unique:roles',
            'name' => 'required|string|max:255|unique:roles',
        ]);
        
        $role = Role::create([
            'slug' => $request->get('slug'),
            'name' => $request->get('name')
        ]);
        
        //Проверка на наличие полученного от формы значения поля с name="permissions"
        if($request->input('permissions')) :
            $role->permissions()->attach($request->input('permissions'));
        endif;
        
        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(/*Role $role*/ int $id)
    {
        //return new RoleResource(Role::FindOrFail($id));
        return new RoleRelationResource(Role::FindOrFail($id));
        
        //return new RoleResource($role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $role->name = $request->get('name');
        $role->save();
        
        //Если список разрешений операций пуст - отсоединяем
        $role->permissions()->detach();
        //Проверка на наличие полученного от формы значения поля с name="roles"
        if($request->input('permissions')) :
            $role->permissions()->attach($request->input('permissions'));
        endif;
        
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->permissions()->detach();
        $role->delete();
        
        return new RoleCollection(Role::all());
        //return response()->json('successfully deleted');
    }
}
