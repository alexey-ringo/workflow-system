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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'title' => 'required|string|max:50|unique:permissions',
            'name' => 'required|string|max:50|unique:permissions',
        ]);
        
        $permission = Permission::create([
            'title' => $request->input('title'),
            'name' => $request->input('name'),
        ]);
        
        if($permission) {
            return response()->json(['message' => 'Новая политика "' . $permission->title . '" успешно создана']);
        }
        else {
            return response()->json(['message' => 'Внутренняя ошибка сервера при создании нового разрешения операции!'], 500);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $permission = Permission::find($id);
        if($permission) {
            return new PermissionResource($permission);
        }
        else {
            return response()->json(['message' => 'Разрешение операции с идентификатором id ' . $id . ' не найдено!'], 422);
        }
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
            'title' => 'required|string|max:50',
            'name' => 'required|string|max:50',
        ]);
        
        $permission->title = $request->input('title');
        $permission->name = $request->input('name');
        if($permission->save()) {
            return response()->json(['message' => 'Изменения для разрешения операции "' . $permission->title . '" успешно применены']);
        }
        else {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений нового разрешения операции!'], 500);
        }
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
