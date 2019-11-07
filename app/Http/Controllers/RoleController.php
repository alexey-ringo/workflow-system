<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\Role\RoleRelationResource;
use Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('index', Role::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр списка политик безопасности!'], 422);
        }
        //return new RoleCollection(Role::all());
        return RoleResource::collection(Role::with('permissions')->get());
        //return RoleResource::collection(Role::all());
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('store', Role::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на создание новой политики безопасности!'], 422);
        }
        $validator = $request->validate([
            'title' => 'required|string|max:50|unique:roles'
        ]);
        
        $role = Role::create([
            'title' => $request->input('title')
        ]);
        
        if(empty($role)) {
            return response()->json(['message' => 'Внутренняя ошибка сервера при создании новой политики безопасности!'], 500);
        }
        
        //Проверка на наличие полученного от формы значения поля с name="permissions"
        if($request->input('permissions')) :
            $role->permissions()->attach($request->input('permissions'));
        endif;
        
        return response()->json(['message' => 'Новая политика безопасности "' . $role->title . '" успешно создана']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if(Gate::denies('show', Role::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр данной политики безопасности!'], 422);
        }
        
        $role = Role::find($id);
        if($role) {
            return new RoleRelationResource($role);
        }
        else {
            return response()->json(['message' => 'Политика безопасности с идентификатором id ' . $id . ' не найдена!'], 422);
        }
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
        if(Gate::denies('update', Role::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на редактирование данной политики безопасности!'], 422);
        }
        
        $validator = $request->validate([
            'title' => 'required|string|max:50',
        ]);
        
        $role->title = $request->input('title');
        
        if(empty($role->save())) {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений новой политики безопасности!'], 500);
        }
        
        //Если список разрешений операций пуст - отсоединяем
        $role->permissions()->detach();
        //Проверка на наличие полученного от формы значения поля с name="roles"
        if($request->input('permissions')) :
            $role->permissions()->attach($request->input('permissions'));
        endif;
        
        return response()->json(['message' => 'Изменения для политики безопасности "' . $role->title . '" успешно применены']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if(Gate::denies('destroy', Role::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на удаление данной политики безопасности!'], 422);
        }
        
        $role->permissions()->detach();
        $role->delete();
        
        return new RoleCollection(Role::all());
        //return response()->json('successfully deleted');
    }
}