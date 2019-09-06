<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Models\Process;
use App\Models\Route;
use App\Models\Comment;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Services\TaskResponse;

use Event;
use App\Events\Tasks\onCreateEvent;
use App\Events\Tasks\onUpdateEvent;

use Exception;
use ErrorException;
use App\Exceptions\WorkflowException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class TaskService
{
    public function createFirstTask(Request $request, User $user): TaskResponse
    {
        //После валидации будет излишним
        //if($request->get('route') != 1) {
        //    return null;
        //}
        //Првоерка на единичность задач по контракту с роутом = 1
        
        $route = Route::where('value', $request->route)->first();
        if(!$route) {
            $message = 'Маршрут обработки задач не найден!';
            return new TaskResponse($message);
        }
        
        $firstProcessSequence = 1;
        $process = Process::getProcess($route, $firstProcessSequence);
        if(!$process) {
            $message = 'Стартовый процесс в выбранном маршруте обработки задач не найден!';
            return new TaskResponse($message);
        }
        
        try {
            $task = Task::create([
                'task' => $this->createTaskNum(),
                'task_sequence' => $this->createTaskSeq(0),
                'route' => $route->value,
                'process_sequence' => $firstProcessSequence,
                'title' => $route->name,
                'description' => $request->description,
                'status' => 1,
                'process_id' => $process->id,
                'contract_id' => $request->contract_id,
                'creating_user_id' => $user->id,
                'process_name' => $process->name,
                'process_slug' => $process->slug,
                'creating_user_name' => $user->name,
                'creating_user_email' => $user->email,
                'deadline' => \Carbon\Carbon::now()->format('dmyHi')
            ]);
            if(!$task) {
                $message = 'Ошибка БД при создании новой задачи!';
                return new TaskResponse($message);
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка БД при создании новой задачи!';
            return new TaskResponse($message);
	    }
        
        
        try {
            $closedTask = $this->closeTask($task, $user);
            if(!$closedTask) {
                $message = 'Ошибка БД при автосохранении первоначального процесса по задаче № ' . $task->task;
                return new TaskResponse($message);
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка БД (исключение) при автосохранении первоначального процесса по задаче № ' . $task->task;
            return new TaskResponse($message);
	    }
        
        $nextProcess = $closedTask->process->getNextProcess($route->id); //настройка работы исключений - убрать id!!!
        if(!$nextProcess) {
            $task->delete();
            $message = 'Не найден следующий процесс обработки данной задачи!';
            return new TaskResponse($message);
        }
        
        try {
            $nextTask = Task::create([
                'task' => $closedTask->task,
                'task_sequence' => $this->createTaskSeq($closedTask->task_sequence),
                'route' => $closedTask->route,
                'process_sequence' => $nextProcess->sequence,
                'title' => $closedTask->title,
                'description' => $request->description,
                'status' => 1,
                'process_id' => $nextProcess->id,
                'contract_id' => $closedTask->contract_id,
                'creating_user_id' => $user->id,
                'process_name' => $nextProcess->name,
                'process_slug' => $nextProcess->slug,
                'creating_user_name' => $user->name,
                'creating_user_email' => $user->email,
                'deadline' => \Carbon\Carbon::now()->format('dmyHi')
            ]);
            if(!$nextTask) {
                $message = 'Ошибка БД при создании следующего процесса "' . $nextProcess->name . '" для задачи № ' . $task->task;
                return new TaskResponse($message);
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $task->delete();
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
                $task->delete();
	            report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка БД при создании следующего процесса "' . $nextProcess->name . '" для задачи № ' . $task->task;
            return new TaskResponse($message);
	    }
	    
	    
	    try {
	        Event::dispatch(new onCreateEvent($nextTask));
	    }
	    catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка при генерации события создания новой задачи!';
            return new TaskResponse($message);
	    }
	    
        $message = 'Новая задача № ' . $nextTask->task . ' успешно создана и передана в следующий процесс обработки "' . $nextTask->process_name . '"';
        return new TaskResponse($message, $nextTask);
    }
    
    
    
    
    public function createNextTask(
                                    Request $request,
                                    Task $task, 
                                    User $user, 
                                    Process $process
                                ): TaskResponse
    {
        
        //Закрытие предидущего последнего sequence
        try {
            $closedTask = $this->closeTask($task, $user);
            if(!$closedTask) {
                $message = 'Ошибка БД при сохранении и закрытии текущего процесса "' . $task->process_name . '" по задаче № ' . $task->task;
                return new TaskResponse($message);
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка БД (исключение) при сохранении и закрытии текущего процесса "' . $task->process_name . '" по задаче № ' . $task->task;
            return new TaskResponse($message);
	    }
        
        
        if($request->get('destination') == 3) {
            try {
                $closedTask = $this->closeTask($task, $user);
                if(!$closedTask) {
                    $message = 'Ошибка БД при закрытии задачи № '. $task->task . ' в процессе "' . $task->process_name . '"';
                    return new TaskResponse($message);
                }
                $message = 'Задача № ' . $closedTask->task . ' успешно завершена и закрыта';
                return new TaskResponse($message, $closedTask);
            }
            catch(Exception $exception) {
	            if ($exception instanceof ErrorException) {
                    $e = new WorkflowException;
                    $e->report($exception);
                }
                elseif($exception instanceof FatalThrowableError) {
	                report($exception);
                }
                else {
	                report($exception);
                }
	            $message = 'Ошибка БД (исключение) при закрытии задачи № '. $task->task . ' в процессе "' . $task->process_name . '"';
                return new TaskResponse($message);
	        }
        }
        
        try {
            $nextTask = Task::create([
                'task' => $closedTask->task,
                'task_sequence' => $this->createTaskSeq($closedTask->task_sequence),
                'route' => $closedTask->route,
                'process_sequence' => $process->sequence,
                'title' => $closedTask->title,
                'description' => $closedTask->description,
                'status' => 1,
                'process_id' => $process->id,
                'contract_id' =>$closedTask->contract_id,
                'creating_user_id' => $user->id,
                'process_name' => $process->name,
                'process_slug' => $process->slug,
                'creating_user_name' => $user->name,
                'creating_user_email' => $user->email,
                'deadline' => \Carbon\Carbon::now()->format('dmyHi')
            ]);
            if(!$nextTask) {
                switch ($request->get('destination')) {
                    case 1:
                        $message = 'Ошибка БД при создании следующего процесса "' . $process->name . '" для задачи № ' . $task->task;
                        return new TaskResponse($message);
                        break;
                    case 2:
                        $message = 'Ошибка БД при возврате в предидущий процесс "' . $process->name . '" для задачи № ' . $task->task;
                        return new TaskResponse($message);
                        break;
                    default:
                        $message = 'Ошибка БД при создании процесса "' . $process->name . '" для задачи № ' . $task->task;
                        return new TaskResponse($message);
                }
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка БД при создании процесса "' . $process->name . '" для задачи № ' . $task->task;
            return new TaskResponse($message);
	    }
        
        try {
            Event::dispatch(new onUpdateEvent($task, $nextTask));
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка при генерации события обработки задачи № ' . $task->task . ' в процессе "' . $process->name . '"';
            return new TaskResponse($message);
	    }
        switch ($request->get('destination')) {
            case 1:
                $message = 'Процесс "' . $closedTask->process_name . '" по задаче № ' . $nextTask->task . ' успешно выполнен и задача передана в следующий процесс обработки "' . $process->name . '"';
                return new TaskResponse($message, $nextTask);
                break;
            case 2:
                $message = 'Процесс "' . $closedTask->process_name . '" по задаче № ' . $nextTask->task . ' успешно выполнен и задача возвращена в предидущий процесс обработки "' . $process->name . '"';
                return new TaskResponse($message, $nextTask);
                break;
            default:
                $message = 'Процесс "' . $closedTask->process_name . '" по задаче № ' . $nextTask->task . ' успешно выполнен и задача передана в процесс обработки "' . $process->name . '"';
                return new TaskResponse($message, $nextTask);
        }
    }
    
    
    public function closeTask(Task $task, User $user): ?Task 
    {
        //Закрытие предидущего последнего sequence
        $task->status = 0;
        $task->closing_user_id = $user->id;
        $task->closing_user_name = $user->name;
        $task->closing_user_email = $user->email;
        if($task->save()) {
            return $task;
        }
        else {
            return null;
        }
    }
    
    
    public function createTaskNum(): int 
    {
        $lastTask = Task::all()->max('task');
        return $lastTask + 1;
    }
    
    
    public function createTaskSeq(int $nextTaskSeq): int 
    {
        //Нужна проверка на существования соответствующего процесса
        return $nextTaskSeq + 1;
    }
    
    
    public function createComment(Request $request, Task $task, User $user): TaskResponse
    {
        try {
            $comment = Comment::create([
                'task_num' => $task->task,
                'task_seq_num' => $task->task_sequence,
                'task_id' => $task->id,
                'comment_seq' => $this->createCommentSeq($task),
                'common_comment_seq' => $this->createCommonCommentSeq($task),
                'comment' => $request->get('comment'),
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email
            ]);
            if(!$comment) {
                $message = 'Ошибка при добавлении комментария по задаче № ' . $task->task;
                return new TaskResponse($message);    
            }
            $message = 'Комментарий по задаче № ' . $task->task . ' был успешно добавлен';
            return new TaskResponse($message, $task);
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка (исключение) при добавлении комментария по задаче № ' . $task->task;
            return new TaskResponse($message);
	    }
    }
    
    
    public function createCommentSeq(Task $task): int 
    {
        return $task->comments->count() + 1;
    }
    
    
    public function createCommonCommentSeq(Task $task): int
    {
        return Comment::getAllCommentsForTask($task)->count() + 1;
        
    }
}