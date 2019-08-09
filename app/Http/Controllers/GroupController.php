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
        return GroupResource::collection(Group::with('missions')->get());
        //return GroupResource::collection(Group::all());
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
            'slug' => 'required|string|max:255|unique:groups',
            'name' => 'required|string|max:255|unique:groups',
        ]);
        
        $group = Group::create([
            'slug' => $request->get('slug'),
            'name' => $request->get('name')
        ]);
        
        //Проверка на наличие полученного от формы значения поля с name="missions"
        if($request->input('missions')) :
            $group->missions()->attach($request->input('missions'));
        endif;
        
        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(/*Group $group*/ int $id)
    {
        //return new GroupResource(Group::FindOrFail($id));
        return new GroupRelationResource(Group::FindOrFail($id));
        
        //return new GroupResource($group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
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
            'name' => 'required|string|max:255',
        ]);
        
        $group->name = $request->get('name');
        $group->save();
        
        //Если список разрешений операций пуст - отсоединяем
        $group->missions()->detach();
        //Проверка на наличие полученного от формы значения поля с name="roles"
        if($request->input('missions')) :
            $group->missions()->attach($request->input('missions'));
        endif;
        
        return new GroupResource($group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->missions()->detach();
        $group->delete();
        
        return new GroupCollection(Group::all());
        //return response()->json('successfully deleted');
    }
}
