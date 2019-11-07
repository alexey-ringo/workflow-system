<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Process;

use App\Services\WorkflowService;

use Illuminate\Http\Request;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\Task\TaskCustomResource;
use App\Http\Resources\Task\TaskCollection;
use Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::denies('index', Task::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр списка задач!'], 422);
        }
        $currentUser = $request->user('api');
      
        return (new TaskCollection(Process::with('tasks.contract.customer', 'groups.users')
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
    public function store(Request $request, WorkflowService $workflowService)
    {
        if(Gate::denies('store', Task::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на создание новой задачи!'], 422);
        }
        
        $createdTask = $workflowService->createFirstTask();
        
        return response()->json(['message' => $createdTask->getMessage()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::denies('show', Task::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр данной задачи!'], 422);
        }
        
        $task = Task::find($id);
        if(!$task) {
            return response()->json(['message' => 'Запрашиваемая задача с идентификатором id ' . $id . ' не найдена!'], 422);
        }
        $isSequenceFirst = $task->process->isSequenceFirst($task->route);
        
        $prevProcess = $task->process->getPrevProcess($task->route);
        if(!$prevProcess) {
            return response()->json(['message' => 'Для задачи № ' . $task->task . ' "' . $task->title . '" отсутствует предидущий процесс!'], 422);
        }
      
        $isSequenceLast = $task->process->isSequenceLast($task->route);
        $nextProcess = $task->process->getNextProcess($task->route);
        
        if($isSequenceLast) {
            return new TaskCustomResource($task, $prevProcess->id, $task->process_id, $prevProcess->title, $task->process->title, $isSequenceFirst, $isSequenceLast);
        }
        
        if(!$nextProcess) {
            return response()->json(['message' => 'Для задачи № ' . $task->task . ' "' . $task->title . '" отсутствует следуюший процесс!'], 422);
        }
        
        return new TaskCustomResource($task, $prevProcess->id, $nextProcess->id, $prevProcess->title, $nextProcess->title, $isSequenceFirst, $isSequenceLast);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, /*Task $task, */WorkflowService $workflowService)
    {
        if(Gate::denies('update', Task::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на редактирование данной задачи!'], 422);
        }
        
        $task = Task::find($request['id']);
        if(!$task) {
            return response()->json(['message' => 'Не определена текущая задача!'], 422);                                        
        }
        
        $currentProcess = Process::find($request['currentProcessId']);
        if(!$currentProcess) {
            return response()->json(['message' => 'Не определен текущий процесс!'], 422);                                        
        }
       
        $updatedTask = $workflowService->createNextTask($task, $currentProcess);

        return response()->json(['message' => $updatedTask->getMessage()]);
    }
}
