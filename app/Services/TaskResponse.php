<?php

namespace App\Services;

use App\Models\Task;


class TaskResponse
{
    private $hasError = false;
    private $task;
    private $message;
    
    public function __construct(string $message, Task $task = null)
    {
        if($task) {
            $this->hasError = false;
        }
        else {
            $this->hasError = true;
        }
        $this->task = $task;
        $this->message = $message;
        
    }
    
    public function getError(): bool
    {
        return $this->hasError;
    }
    
    public function getTask(): ?Task
    {
        return $this->task;
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}