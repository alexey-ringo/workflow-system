<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Models\Mission;
use App\Services\UserService;
use App\Services\MissionService;

class TaskService
{
    private $missionService;
    
    public function __construct(MissionService $missionService)
    {
        $this->missionService = $missionService;
    }
    
    /*
    public static function createNewTask(string $title, string $description, User $user): Task
    {
        return Task::create([
            'task' => self::createTaskNum(),
            'sequence' => 1//self::createTaskSequence(),
            'title' => $title,
            'description' => $description,
            'status' => 2,
            'mission_id' => 2,
            'user_id' => $user->id,
            'mission_name' => MissionService::getMissionName(2),
            'user_name' => $user->name,
            'user_email' => $user->email,
            'deadline' => \Carbon\Carbon::now()->format('dmyHi')
        ]);
    }
    */
    
    public function createNewTask(string $title, string $description, User $user): Task
    {
        return Task::create([
            'task' => $this->createTaskNum(),
            'sequence' => 2,
            'title' => $title,
            'description' => $description,
            'status' => 1,
            'mission_id' => $this->missionService->getNextMissionId(1),
            'creating_user_id' => $user->id,
            'mission_name' => $this->missionService->getNextMissionName(1),
            'creating_user_name' => $user->name,
            'creating_user_email' => $user->email,
            'deadline' => \Carbon\Carbon::now()->format('dmyHi')
        ]);
    }
    
    public function createNextSeqTask(Task $task, User $user, int $nextMissionId, string $nextMissionName/*, int $nextSequence*/): Task
    {
        //Закрытие очередного sequence
        $task->status = 0;
        $task->closing_user_id = $user->id;
        $task->closing_user_name = $user->name;
        $task->closing_user_email = $user->email;
        $task->save();
        
        
        return Task::create([
            'task' => $task->task,
            'sequence' => $this->missionService->getSequence($nextMissionId),
            //'sequence' => $nextSequence,
            'title' => $task->title,
            'description' => $task->description,
            'status' => 1,
            //'mission_id' => $this->missionService->getMissionId($nextSequence),
            'mission_id' => $nextMissionId,
            'creating_user_id' => $user->id,
            'mission_name' => $nextMissionName,
            'creating_user_name' => $user->name,
            'creating_user_email' => $user->email,
            'deadline' => \Carbon\Carbon::now()->format('dmyHi')
        ]);
    }
    
    public function closeTask(Task $task, User $user): Task 
    {
        //Закрытие последнего sequence
        $task->status = 0;
        $task->closing_user_id = $user->id;
        $task->closing_user_name = $user->name;
        $task->closing_user_email = $user->email;
        $task->save();
        return $task;
    }
    
    public function createTaskNum(): int 
    {
        $lastTask = Task::all()->max('task');
        return $lastTask + 1;
    }
}