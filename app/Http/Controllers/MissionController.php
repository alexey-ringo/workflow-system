<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;
use App\Http\Resources\MissionCollection;
use App\Http\Resources\MissionResource;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new MissionCollection(Mission::all());
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
            'name' => 'required|string|max:255|unique:missions',
            'queue' => 'required|unique:missions',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        
        $mission = Mission::create([
            'name' => $request->get('name'),
            'queue' => $request->get('queue'),
            'is_super' => $request->get('is_super'),
            'is_final' => $request->get('is_final'),
        ]);
        
        return new MissionResource($mission);   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Mission  $mission
     * @return \Illuminate\Http\Response
     */
    public function show(/*Mission $mission*/ int $id)
    {
        return new MissionResource(Mission::FindOrFail($id));
        //return new MissionResource($mission);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mission  $mission
     * @return \Illuminate\Http\Response
     */
    public function edit(Mission $mission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mission  $mission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mission $mission)
    {
        
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'queue' => 'required',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        /*
        $mission->name = $request->get('name');
        $mission->queue = $request->get('queue');
        $mission->is_super = $request->get('is_super');
        $mission->is_final = $request->get('is_final');
        $mission->save();
        */
        $mission->update($request->all());
        
        return new MissionResource($mission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mission  $mission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mission $mission)
    {
        $mission->groups()->detach();
        $mission->delete();
        
        return new MissionCollection(Mission::all());
        //return response()->json('successfully deleted');
    }
}
