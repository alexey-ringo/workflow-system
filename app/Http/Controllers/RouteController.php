<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Process;
use Illuminate\Http\Request;
use App\Http\Resources\Route\RouteCollection;
use App\Http\Resources\Route\RouteResource;
use Gate;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::denies('index', Route::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр списка маршрутов']);
        }
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
        if(Gate::denies('store', Route::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на создание нового маршрута']);
        }
        
        $route = Route::create([
            'title' => $request->input('title'),
            'value' => $request->input('value'),
            'description' => $request->input('description'),
            'is_active' => $request->input('is_active'),
        ]);
        
        if($route) {
            return response()->json(['message' => 'Новый маршрут успешно создан']);
        }
        else {
            return response()->json(['message' => 'Внутранняя ошибка при создании нового маршрута!'], 500);
        }  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if(Gate::denies('show', Route::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр данного маршрута']);
        }
        
        $route = Route::find($id);
        if($route) {
            return new RouteResource($route);
        }
        else {
            return response()->json(['message' => 'Маршрут с идентификатором id ' . $id . ' не найден!'], 422);
        }
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
        if(Gate::denies('update', Route::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на редактирование данного маршрута']);
        }
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'sequence' => 'required',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        $route->title = $request->input('title');
        $route->value = $request->input('value');
        $route->description = $request->input('description');
        $route->is_active = $request->input('is_active');
        
        
        //$process->update($request->all());
        
        if($route->save()) {
            return response()->json(['message' => 'Изменения для маршрута успешно применены']);
        }
        else {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений в маршруте!'], 500);
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
        if(Gate::denies('destroy', Route::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на удаление данного маршрута']);
        }
        
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
