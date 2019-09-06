<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Process;

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
        
        return (new TaskCollection(Process::with('tasks', 'groups.users')
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
        
        $createdTask = $taskService->createFirstTask($request, $currentUser);
        
        return response()->json(['data' => [
                                        'error' => $createdTask->getError(),
                                        'task' => $createdTask->getTask(),
                                        'message' => $createdTask->getMessage()
                                    ]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $isSequenceFirst = $task->process->isSequenceFirst($task->route);
        
        $prevProcess = $task->process->getPrevProcess($task->route);
        if(!$prevProcess) {
            return response()->json(['data' => [
                                        'error' => true,
                                        'task' => $task->task,
                                        'title' => $task->title,
                                        'message' => 'отсутствует предидущий процесс'
                                    ]]);
        }
      
        $isSequenceLast = $task->process->isSequenceLast($task->route);
        $nextProcess = $task->process->getNextProcess($task->route);
        
        if($isSequenceLast) {
            return new TaskCustomResource($task, $prevProcess->id, $task->process_id, $prevProcess->name, $task->process_name, $isSequenceFirst, $isSequenceLast);
        }
        
        if(!$nextProcess) {
            return response()->json(['data' => [
                                        'error' => true,
                                        'task' => $task->task,
                                        'title' => $task->title,
                                        'message' => 'отсутствует следующий процесс'
                                            ]]);
        }
        
       
        return new TaskCustomResource($task, $prevProcess->id, $nextProcess->id, $prevProcess->name, $nextProcess->name, $isSequenceFirst, $isSequenceLast);
        
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
        $currentProcess = Process::find($request['currentProcessId']);
        if(!$currentProcess) {
            return response()->json(['data' => 0]);
        }
       
        $updatedTask = $taskService->createNextTask(
                                                        $request,
                                                        $task, 
                                                        $currentUser, 
                                                        $currentProcess
                                                        );
        return response()->json(['data' => [
                                        'error' => $updatedTask->getError(),
                                        'task' => $updatedTask->getTask(),
                                        'message' => $updatedTask->getMessage()
                                    ]]);
       
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
