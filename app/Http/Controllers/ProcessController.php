<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;
use App\Http\Requests\ProcessRequest;
use App\Http\Resources\Process\ProcessCollection;
use App\Http\Resources\Process\ProcessResource;
use Gate;

class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('index', Process::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр списка процессов!']);
        }
        
        return new ProcessCollection(Process::with('route')->get());
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProcessRequest $request)
    {
        if(Gate::denies('store', Process::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на создание нового процесса!']);
        }
        $process = Process::create($request->only(['route_id', 'title', 'deadline', 'sequence', 'is_super', 'is_final', 'is_active']));
        
        if($process) {
            return response()->json(['message' => 'Новый процесс успешно создан']);
        }
        else {
            return response()->json(['message' => 'Внутранняя ошибка при создании нового процесса!'], 500);
        }    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if(Gate::denies('show', Process::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр данного процесса!']);
        }
        
        $process = Process::find($id);
        
        if($process) {
            return new ProcessResource($process);
        }
        else {
            return response()->json(['message' => 'Процесс с идентификатором id ' . $id . ' не найден!'], 422);
        }        
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function update(ProcessRequest $request, Process $process)
    {
        if(Gate::denies('update', Process::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на редактирование данного процесса!']);
        }
        
        if($process->update($request->only(['route_id', 'title', 'deadline', 'sequence', 'is_super', 'is_final', 'is_active']))) {
            return response()->json(['message' => 'Изменения для процесса успешно применены']);
        }
        else {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений в процессе!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    //public function destroy(Process $process)
    //{
    //    if(Gate::denies('destroy', Process::class)) {
    //        return response()->json(['message' => 'У Вас недостаточно прав на удаление данного процесса!']);
    //    }
    //    
    //    $process->groups()->detach();
    //    $process->delete();
    //    
    //    return new ProcessCollection(Process::all());
    //}
}
