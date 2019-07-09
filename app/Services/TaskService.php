<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Models\Mission;
use App\Services\UserService;

class TaskService
{
    public function createNewTask(string $title, string $description, User $user): Task
    {
        return  Task::create([
            'task' => $this->createTaskNum(),
            'sequence' => 2,
            'title' => $title,
            'description' => $description,
            'status' => 1,
            //'mission_id' => $this->missionService->getNextMissionId(1),
            'mission_id' => Mission::nextMissionId(1),
            'creating_user_id' => $user->id,
            //'mission_name' => $this->missionService->getNextMissionName(1),
            'mission_name' => Mission::nextMissionName(1),
            'creating_user_name' => $user->name,
            'creating_user_email' => $user->email,
            'deadline' => \Carbon\Carbon::now()->format('dmyHi')
        ]);
        
        
    }
    
    public function createNextSeqTask(Task $task, 
                                        User $user, 
                                        int $nextMissionId, 
                                        string $nextMissionName,
                                        int $destination): Task
    {
        //Закрытие очередного sequence
        $task->status = 0;
        $task->closing_user_id = $user->id;
        $task->closing_user_name = $user->name;
        $task->closing_user_email = $user->email;
        $task->save();
        
        if($destination == 3) {
            return $this->closeTask($task, $user);
        }
        
        return Task::create([
            'task' => $task->task,
            'sequence' => Mission::find($nextMissionId)->sequence,
            //'sequence' => $task->mission->getNextSequence($nextMissionId),
            //'sequence' => $this->missionService->getSequence($nextMissionId),
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