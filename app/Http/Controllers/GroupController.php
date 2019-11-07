<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Group\GroupRelationResource;
use App\Http\Resources\Group\GroupCollection;
use Gate;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('index', Group::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр списка рабочих групп!'], 422);
        }
        
        //return GroupResource::collection(Group::with('processes')->get());
        return new GroupCollection(Group::with('processes')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        if(Gate::denies('store', Group::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на создание новой рабочей группы!'], 422);
        }
        
        $group = Group::create([
            'title' => $request->input('title')
        ]);
        
        if(empty($group)) {
            return response()->json(['message' => 'Внутренняя ошибка сервера при создании новой рабочей группы!'], 500);
        }
        
        //Проверка на наличие полученного от формы значения поля с name="processes"
        if($request->input('processes')) :
            $group->processes()->attach($request->input('processes'));
        endif;
        
        return response()->json(['message' => 'Новая рабочая группа "' . $group->title . '" успешно создана']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if(Gate::denies('show', Group::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр данной рабочей группы!'], 422);
        }
        
        $group = Group::find($id);
        
        if($group) {
            return new GroupRelationResource($group);
        }
        else {
            return response()->json(['message' => 'Рабочая группа с идентификатором id ' . $id . ' не найдена!'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, Group $group)
    {
        if(Gate::denies('update', Group::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на редактирование данной рабочей группы!'], 422);
        }
        
        if(empty($group->update($request->only('title'))))
        {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений новой рабочей группы!'], 500);
        }
        
        //Если список разрешений операций пуст - отсоединяем
        $group->processes()->detach();
        //Проверка на наличие полученного от формы значения поля с name="processes"
        if($request->input('processes')) :
            $group->processes()->attach($request->input('processes'));
        endif;
        
        return response()->json(['message' => 'Изменения для рабочей группы "' . $group->title . '" успешно применены']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        if(Gate::denies('destroy', Group::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на удаление данной рабочей группы!'], 422);
        }
        $group->processes()->detach();
        $group->delete();
        
        return new GroupCollection(Group::all());
    }
}
