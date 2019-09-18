<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Process;
use Illuminate\Http\Request;
use App\Http\Resources\Route\RouteCollection;
use App\Http\Resources\Route\RouteResource;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('filter')) {
            return new RouteCollection(Route::where('value', '>', 1)->get());
        }
        else {
            return new RouteCollection(Route::all());
        }
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:processes',
            'sequence' => 'required|unique:processes',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        $route = Route::create([
            'name' => $request->input('name'),
            'value' => $request->input('value'),
            'description' => $request->input('description'),
            'in_use' => $request->input('in_use'),
        ]);
        
        if($route) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route/* int $id*/)
    {
        return new RouteResource($route);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'sequence' => 'required',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        $route->name = $request->input('name');
        $route->value = $request->input('value');
        $route->description = $request->input('description');
        $route->in_use = $request->input('in_use');
        $route->save();
        
        //$process->update($request->all());
        
        if($route) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route)
    {
        $processes = Process::where('route_id', $route->id)->get();
        
        foreach($processes as $processItem) {
            $processItem->groups()->detach();
        }
        
        if($route->delete()) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }  
    }
}
