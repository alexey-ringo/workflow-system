<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Mission;

use App\Services\TaskService;

use Illuminate\Http\Request;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\Task\TaskCustomResource;
use App\Http\Resources\Task\TaskCollection;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentUser = $request->user('api');
        
        return (new TaskCollection(Mission::with('tasks', 'groups.users')
                                        ->whereHas('groups.users', function($q) use($currentUser) {
                                            $q->where('users.id', $currentUser->id);
                                        }
                                    )->get()/*->load('comments')*/))
                                    ->additional(['meta' => 
                                                        [
                                                            'canTaskCreate' => $currentUser->canFirstCreate()
                                                        ]
                                                ]);
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
        if(!$currentUser) {
            return response()->json(['data' => 0]);
        }
        /*
        $validator = $request->validate([
            'slug' => 'required|string|max:255|unique:groups',
            'name' => 'required|string|max:255|unique:groups',
        ]);
        */
        $createdTask = $taskService->createNewTask($request['title'], $request['description'], $currentUser);
        
        if($createdTask) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $prevMissionId = $task->mission->prevMissionId();
        $nextMissionId = $task->mission->nextMissionId();
        $prevMissionName = $task->mission->prevMissionName();
        $nextMissionName = $task->mission->nextMissionName();
        $isSequenceFirst = $task->mission->isSequenceFirst();
        $isSequenceLast = $task->mission->isSequenceLast();
        
        return new TaskCustomResource($task, $prevMissionId, $nextMissionId, $prevMissionName, $nextMissionName, $isSequenceFirst, $isSequenceLast);
        
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task, TaskService $taskService)
    {
        $currentUser = $request->user('api');
        if(!$currentUser) {
            return response()->json(['data' => 0]);
        }
        $currentMission = Mission::find($request['currentMissionId']);
        if(!$currentMission) {
            return response()->json(['data' => 0]);
        }
       
        $updatedTask = $taskService->createNextSeqTask(
                                                        $task, 
                                                        $currentUser, 
                                                        $currentMission,
                                                        $request['destination']
                                                        );
        
        if($updatedTask) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }
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
