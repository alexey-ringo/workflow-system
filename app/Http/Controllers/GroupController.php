<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Group\GroupRelationResource;
use App\Http\Resources\Group\GroupCollection;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return new GroupCollection(Group::all());
        return GroupResource::collection(Group::with('processes')->get());
        //return GroupResource::collection(Group::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
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
    public function update(Request $request, Group $group)
    {
        $validator = $request->validate([
            'title' => 'required|string|max:50',
        ]);
        
        $group->title = $request->input('title');
        
        if(empty($group->save())) {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений новой рабочей группы!'], 500);
        }
        
        //Если список разрешений операций пуст - отсоединяем
        $group->processes()->detach();
        //Проверка на наличие полученного от формы значения поля с name="roles"
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
        $group->processes()->detach();
        $group->delete();
        
        return new GroupCollection(Group::all());
        //return response()->json('successfully deleted');
    }
}
