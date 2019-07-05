<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Mission;

use App\Services\TaskService;
use App\Services\UserService;
use App\Services\MissionService;


use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCustomResource;
use App\Http\Resources\TaskCollection;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UserService $userService)
    {
        $currentUser = $request->user('api');
        
        return (new TaskCollection(Task::all()/*->load('comments')*/))
                ->additional(['meta' => [
                    'canTaskCreate' => $userService->canFirstCreate($currentUser),
                ]]);
        //return response()->json(['isFirstStep' => $currentUser->hasSequences()]);
        //return response()->json(['isFirstStep' => Mission::missionsForUser($currentUser)]);
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
    public function store(Request $request, TaskService $taskService)
    {
        $currentUser = User::find($request->user('api')->id);
        
        /*
        $validator = $request->validate([
            'slug' => 'required|string|max:255|unique:groups',
            'name' => 'required|string|max:255|unique:groups',
        ]);
        */
        $createdTask = $taskService->createNewTask($request['title'], $request['description'], $currentUser);
        
        return new TaskResource($createdTask);
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task, MissionService $missionService)
    {
        $prevMissionId = $missionService->getPrevMissionId($task->sequence);
        $nextMissionId = $missionService->getNextMissionId($task->sequence);
        $prevMissionName = $missionService->getPrevMissionName($task->sequence);
        $nextMissionName = $missionService->getNextMissionName($task->sequence);
        $isSequenceLast = $missionService->isSequenceLast($task->sequence);
        return new TaskCustomResource($task, $prevMissionId, $nextMissionId, $prevMissionName, $nextMissionName, $isSequenceLast);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task, TaskService $taskService/*, MissionService $missionService*/)
    {
        $currentUser = $request->user('api');
        
        //$nextSequence = $missionService->getSequence($request['execMissionId']);
        $updatedTask;
        if(!$request['isSequenceLast']) {
            $updatedTask = $taskService->createNextSeqTask($task, $currentUser, $request['execMissionId'], $request['execMissionName']/*, $nextSequence*/);
        }
        else {
            $updatedTask = $taskService->closeTask($task, $currentUser);
        }
        
        return new TaskResource($updatedTask);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
