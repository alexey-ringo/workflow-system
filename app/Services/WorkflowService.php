<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Phone;
use App\Models\Customer;
use App\Models\Contract;
use App\Models\User;
use App\Models\Task;
use App\Models\Process;
use App\Models\Route;
use App\Models\Comment;

use App\Services\TaskService;
use App\Services\CustomerResponse;
use App\Services\ContractResponse;
use App\Services\TaskResponse;

use Event;
use App\Events\Customer\onCreateEvent as onCustomerCreateEvent;
use App\Events\Customer\onUpdateEvent as onCustomerUpdateEvent;
use App\Events\Contract\onCreateEvent as onContractCreateEvent;
use App\Events\Contract\onUpdateEvent as onContractUpdateEvent;
use App\Events\Contract\onCloseEvent as onContractCloseEvent;
use App\Events\Tasks\onCreateEvent as onTaskCreateEvent;
use App\Events\Tasks\onUpdateEvent as onTaskUpdateEvent;

use Exception;
use ErrorException;
use Illuminate\Database\QueryException;
use App\Exceptions\WorkflowException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class WorkflowService
{
    private $user;
    
    private $request;
    
    private $contract;
    
    private $task;
    
    private $closedTask;
    
    private $error = false;
    
    private $message;
    
    public function __construct(Request $request, TaskService $taskService)
    {
        $this->request = $request;
        $this->user = User::find($this->request->user('api')->id);
        $this->taskService = $taskService;
    }
    
    public function createNewCustomer(): CustomerResponse
    {
        if(!$this->user) {
            $this->error = true;
            $this->message = 'Не найден текущий пользователь!';
            return new CustomerResponse($this->message);
        }
        
        try {
            $customer = Customer::create([
                'surname' => $this->request->input('surname'),
                'name' => $this->request->input('name'),
                'second_name' => $this->request->input('second_name'),
                'city' => $this->request->input('city'),
                'region' => $this->request->input('region'),
                'street' => $this->request->input('street'),
                'building' => $this->request->input('building'),
                'flat' => $this->request->input('flat'),
                'email' => $this->request->input('email'),
                'description' => $this->request->input('description'),
            ]);
            if(!$customer) {
                $this->message = 'Ошибка при создании нового клиента';
                return new CustomerResponse($this->message);
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            $e = new WorkflowException;
                $e->report($exception);
            }
            else {
	            $e = new WorkflowException;
                $e->report($exception);
            }
            $this->error = true;
	        $this->message = 'Ошибка при создании нового клиента (исключение)!';
            return new CustomerResponse($this->message);
	    }
        
        try {
            $phone = Phone::create([
                'customer_id' => $customer->id,
                'phone' => $this->request->input('phone'),
            ]);
            if(!$phone) {
                $this->error = true;
                $this->message = 'Ошибка при добавлении телефонов для клиента ' . $customer->name . ' ' . $customer->surname;
                $customer->delete();
                return new CustomerResponse($this->message);
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            $e = new WorkflowException;
                $e->report($exception);
            }
            else {
	            $e = new WorkflowException;
                $e->report($exception);
            }
            $this->error = true;
	        $this->message = 'Ошибка при добавлении телефонов для клиента ' . $customer->name . ' ' . $customer->surname . ' (исключение)!';
	        $customer->delete();
            return new CustomerResponse($this->message);
	    }
        
        Event::dispatch(new onCustomerCreateEvent($customer));
        
        $this->request->request->add(['task_route' => 1]);
        $this->request->request->add(['task_description' => 'Первоначальная задача при создании нового клиента']);
      
        /** @var App\Services\ContractResponse $contractResponse */
        $contractResponse = $this->createNewContract($customer);
        if($contractResponse->hasError()) {
            $this->error = true;
            $this->message = 'Ошибка при автоматическом создании контракта для клиента ' . $customer->name . ' ' . $customer->surname . '  ' . $contractResponse->getMessage();
            $customer->delete();
            return new CustomerResponse($this->message);
        }
        
        $this->message = 'Новый клиент ' . $customer->name . ' ' . $customer->surname . ' успешно создан и ему автоматически добавлен новый контракт № ' . /*$contractResponse->getContractNum()*/$this->contract->contract_num;
        return new CustomerResponse($this->message, $customer);
    }
    
    
    
    public function createNewContract(Customer $customer): ContractResponse
    {
        $contractNum = $this->createContractNum();
        if(!$contractNum) {
            $this->error = true;
            $this->message = 'Ошибка выделения номера для нового контракта для клиента ' . $customer->name . ' ' . $customer->surname;
            return new ContractResponse($this->message);
        }
        
        try {
            $this->contract = Contract::create([
                'contract_num' => $contractNum,
                'customer_id' => $customer->id,
            ]);
            if(!$this->contract) {
                $this->error - true;
                $this->message = 'Ошибка при создании нового контракта для клиента ' . $customer->name . ' ' . $customer->surname;
                return new ContractResponse($this->message);
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            $e = new WorkflowException;
                $e->report($exception);
            }
            else {
	            $e = new WorkflowException;
                $e->report($exception);
            }
	        $this->message = 'Ошибка при создании нового контракта для клиента ' . $customer->name . ' ' . $customer->surname . ' (исключение)';
            return new ContractResponse($this->message);
	    }
        
       Event::dispatch(new onContractCreateEvent($this->contract));
        
        $this->firstTaskAdapter();
        if($this->error) {
            $this->message = 'Ошибка при автоматическом создании новой задачи для контракта ' . $this->contract->contract_num . 
                        ' у клиента ' . $customer->name . ' ' . $customer->surname . ' : ' . $taskResponse->getMessage();
            $this->contract->delete();
            return new ContractResponse($this->message);
        }
        
        /** @var App\Services\TaskResponse $taskResponse */
        $taskResponse = $this->createFirstTask();
        if($taskResponse->hasError()) {
            $this->error = true;
            $this->message = 'Ошибка при автоматическом создании новой задачи для контракта № ' . $this->contract->contract_num . ' у клиента ' . $customer->name . ' ' . $customer->surname . ' : "' . $taskResponse->getMessage() . ' "';
            $this->contract->delete();
            return new CustomerResponse($this->message);
        }
        
        $this->message = 'Успешно создан новый контракт № ' . $this->contract->contract_num . ' для клиента ' . $customer->name . ' ' . $customer->surname;
        return new ContractResponse($this->message, $this->contract);
    }
    
    
    public function createFirstTask(): TaskResponse
    {
        if(!$this->user) {
            $this->error = true;
            $this->message = 'Не найден текущий пользователь!';
            return new TaskResponse($this->message);
        }
        //После валидации будет излишним
        //if($this->request->input('route') != 1) {
        //    return null;
        //}
        //Првоерка на единичность задач по контракту с роутом = 1
        
        $route = Route::where('value', $this->request->route)->first();
        if(!$route) {
            $this->message = 'Маршрут обработки задач не найден!';
            return new TaskResponse($this->message);
        }
        
        $firstProcessSequence = 1;
        $process = Process::getProcess($route, $firstProcessSequence);
        if(!$process) {
            $this->message = 'Стартовый процесс в выбранном маршруте обработки задач не найден!';
            return new TaskResponse($this->message);
        }
        
        try {
            $this->task = Task::create([
                'task' => $this->createTaskNum(),
                'task_sequence' => $this->createTaskSeq(),
                'route' => $route->value,
                'process_sequence' => $firstProcessSequence,
                'title' => $route->name,
                'description' => $this->request->description,
                'status' => 1,
                'process_id' => $process->id,
                'contract_id' => $this->request->contract_id,
                'creating_user_id' => $this->user->id,
                'process_name' => $process->name,
                'process_slug' => $process->slug,
                'creating_user_name' => $this->user->name,
                'creating_user_email' => $this->user->email,
                'deadline' => \Carbon\Carbon::now()->format('dmyHi')
            ]);
            if(!$this->task) {
                $this->message = 'Ошибка БД при создании новой задачи!';
                return new TaskResponse($this->message);
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            $e = new WorkflowException;
                $e->report($exception);
            }
            else {
	            $e = new WorkflowException;
                $e->report($exception);
            }
	        $this->message = 'Ошибка БД при создании новой задачи (исключение)!';
            return new TaskResponse($this->message);
	    }
        
        $this->closeTask();
        if($this->error) {
            return new TaskResponse($this->message);
        }
        
        $nextProcess = $this->closedTask->process->getNextProcess($route->id); //настройка работы исключений - убрать id!!!
        if(!$nextProcess) {
            $this->closedTask->delete();
            $this->message = 'Не найден следующий процесс обработки данной задачи!';
            return new TaskResponse($this->message);
        }
        
        try {
            $this->task = Task::create([
                'task' => $this->closedTask->task,
                'task_sequence' => $this->createTaskSeq(),
                'route' => $this->closedTask->route,
                'process_sequence' => $nextProcess->sequence,
                'title' => $this->closedTask->title,
                'description' => $this->request->description,
                'status' => 1,
                'process_id' => $nextProcess->id,
                'contract_id' => $this->closedTask->contract_id,
                'creating_user_id' => $this->user->id,
                'process_name' => $nextProcess->name,
                'process_slug' => $nextProcess->slug,
                'creating_user_name' => $this->user->name,
                'creating_user_email' => $this->user->email,
                'deadline' => \Carbon\Carbon::now()->format('dmyHi')
            ]);
            if(!$this->task) {
                $this->message = 'Ошибка БД при создании следующего процесса "' . $nextProcess->name . '" для задачи № ' . $this->closedTask->task;
                return new TaskResponse($this->message);
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $this->closedTask->delete();
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
                $e = new WorkflowException;
                $this->closedTask->delete();
                $e->report($exception);
            }
            else {
	            $e = new WorkflowException;
                $this->closedTask->delete();
                $e->report($exception);
            }
	        $this->message = 'Ошибка БД при создании следующего процесса "' . $nextProcess->name . '" для задачи № ' . $this->closedTask->task;
            return new TaskResponse($this->message);
	    }
	    
	    Event::dispatch(new onTaskCreateEvent($this->task));
	    
        $this->message = 'Новая задача № ' . $this->task->task . ' успешно создана и передана в следующий процесс обработки "' . $this->task->process_name . '"';
        return new TaskResponse($this->message, $this->task);
    }
    
    
    public function createNextTask(
                                   Task $task,
                                   Process $process
                                ): TaskResponse
    {
        if(!$this->user) {
            $this->error = true;
            $this->message = 'Не найден текущий пользователь!';
            return new TaskResponse($this->message);
        }
        $this->task = $task;
        
        //Закрытие предидущего или последнего sequence
        $this->closeTask();
        if($this->error) {
            $this->task->delete();
            return new TaskResponse($this->message);
        }
        
        if($this->request->input('destination') == 3) {
            $this->message = 'Задача № ' . $this->closedTask->task . ' успешно завершена и закрыта';
            return new TaskResponse($this->message, $this->closedTask);
        }
        
        
        try {
            $this->task = Task::create([
                'task' => $this->closedTask->task,
                'task_sequence' => $this->createTaskSeq(),
                'route' => $this->closedTask->route,
                'process_sequence' => $process->sequence,
                'title' => $this->closedTask->title,
                'description' => $this->closedTask->description,
                'status' => 1,
                'process_id' => $process->id,
                'contract_id' =>$this->closedTask->contract_id,
                'creating_user_id' => $this->user->id,
                'process_name' => $process->name,
                'process_slug' => $process->slug,
                'creating_user_name' => $this->user->name,
                'creating_user_email' => $this->user->email,
                'deadline' => \Carbon\Carbon::now()->format('dmyHi')
            ]);
            if(!$this->task) {
                switch ($this->request->input('destination')) {
                    case 1:
                        $this->message = 'Ошибка БД при создании следующего процесса "' . $process->name . '" для задачи № ' . $this->closedTask->task;
                        return new TaskResponse($this->message);
                        break;
                    case 2:
                        $this->message = 'Ошибка БД при возврате в предидущий процесс "' . $process->name . '" для задачи № ' . $this->closedTask->task;
                        return new TaskResponse($this->message);
                        break;
                    default:
                        $this->message = 'Ошибка БД при создании процесса "' . $process->name . '" для задачи № ' . $this->closedTask->task;
                        return new TaskResponse($this->message);
                }
            }
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            $e = new WorkflowException;
                $e->report($exception);
            }
            else {
	            $e = new WorkflowException;
                $e->report($exception);
            }
	        $this->message = 'Ошибка БД при создании процесса "' . $process->name . '" для задачи № ' . $this->closedTask->task;
            return new TaskResponse($this->message);
	    }
        
        Event::dispatch(new onTaskUpdateEvent($this->closedTask, $this->task));
        
        switch ($this->request->input('destination')) {
            case 1:
                $this->message = 'Процесс "' . $this->closedTask->process_name . '" по задаче № ' . $this->task->task . ' успешно выполнен и задача передана в следующий процесс обработки "' . $process->name . '"';
                return new TaskResponse($this->message, $this->task);
                break;
            case 2:
                $this->message = 'Процесс "' . $this->closedTask->process_name . '" по задаче № ' . $this->task->task . ' успешно выполнен и задача возвращена в предидущий процесс обработки "' . $process->name . '"';
                return new TaskResponse($this->message, $this->task);
                break;
            default:
                $this->message = 'Процесс "' . $this->closedTask->process_name . '" по задаче № ' . $this->task->task . ' успешно выполнен и задача передана в процесс обработки "' . $process->name . '"';
                return new TaskResponse($this->message, $this->task);
        }
    }
    
    
    public function createComment(Task $task): TaskResponse
    {
        try {
            $comment = Comment::create([
                'task_num' => $task->task,
                'task_seq_num' => $task->task_sequence,
                'task_id' => $task->id,
                'comment_seq' => $this->createCommentSeq($task),
                'common_comment_seq' => $this->createCommonCommentSeq($task),
                'comment' => $this->request->input('comment'),
                'user_id' => $this->user->id,
                'user_name' => $this->user->name,
                'user_email' => $this->user->email
            ]);
            if(!$comment) {
                $this->message = 'Ошибка при добавлении комментария по задаче № ' . $task->task;
                return new TaskResponse($this->message);    
            }
            $this->message = 'Комментарий по задаче № ' . $task->task . ' был успешно добавлен';
            return new TaskResponse($this->message, $task);
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
	        $this->message = 'Ошибка (исключение) при добавлении комментария по задаче № ' . $task->task;
            return new TaskResponse($this->message);
	    }
    }
    
    
    
    //--------------------------------------------------//
    
    
    
    
    private function firstTaskAdapter()
    {
        if($this->request->has('task_route')) {
            $this->request->request->add(['route' => $this->request->input('task_route')]);
        }
        else {
            $this->error = true;
            $this->message = 'Ошибка при создании задачи - нет информации о маршруте';
        }
        
        if($this->request->request->has('task_description')) {
            $this->request->request->add(['description' => $this->request->input('task_description')]);
        }
        else {
            $this->error = true;
            $this->message = 'Ошибка при создании задачи - в запросе отсутстует поле с описанием задачи';
        }
        
        if($this->contract) {
            $this->request->request->add(['contract_id' => $this->contract->id]);
        }
        else {
            $this->error = true;
            $this->message = 'Невозможно создать новую задачу - отсутствует информация о номере контракта!';
        }
    }
    
    private function createContractNum(): ?int 
    {
        try {
            $lastContract = Contract::all()->max('contract_num');
            
            return $lastContract + 1;
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            else {
	            report($exception);
            }
            
	        return null;
	    }
    }
    
    private function closeTask() 
    {
        try {
            $this->closedTask = $this->task;
            //Закрытие предидущего или последнего sequence
            $this->closedTask->status = 0;
            $this->closedTask->closing_user_id = $this->user->id;
            $this->closedTask->closing_user_name = $this->user->name;
            $this->closedTask->closing_user_email = $this->user->email;
            if(!$this->closedTask->save()) {
                $this->error = true;
                $this->message = 'Ошибка БД при закрытии процесса "' . $this->task->process_name . '" по задаче № '. $this->task->task;
            }
            $this->message = 'Процесс "' . $this->task->process_name . ' по задаче '. $this->task->task .'" успешно закрыт';
        }
        catch(Exception $exception) {
	        if ($exception instanceof ErrorException) {
                $e = new WorkflowException;
                $e->report($exception);
            }
            elseif($exception instanceof FatalThrowableError) {
	            $e = new WorkflowException;
                $e->report($exception);
            }
            else {
	            $e = new WorkflowException;
                $e->report($exception);
            }
            $this->error = true;
	        $this->message = 'Ошибка БД при закрытии процесса "' . $this->task->process_name . '" по задаче № '. $this->task->task . ' (Исключение)';
	    }
    }
    
    
    private function createTaskNum(): int 
    {
        $lastTask = Task::all()->max('task');
        return $lastTask + 1;
    }
    
    
    private function createTaskSeq(): int 
    {
        //Нужна проверка на существования соответствующего процесса
        return $this->closedTask ? $this->closedTask->task_sequence + 1 : 1;
    }
    
    private function createCommentSeq(Task $task): int 
    {
        return $task->comments->count() + 1;
    }
    
    
    private function createCommonCommentSeq(Task $task): int
    {
        return Comment::getAllCommentsForTask($task)->count() + 1;
    }
}