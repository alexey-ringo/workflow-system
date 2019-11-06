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

use App\Services\CustomerResponse;
use App\Services\ContractResponse;
use App\Services\TaskResponse;
use App\Services\CommentResponse;

use Event;
use App\Events\Customer\onCreateEvent as onCustomerCreateEvent;
use App\Events\Customer\onUpdateEvent as onCustomerUpdateEvent;
use App\Events\Contract\onCreateEvent as onContractCreateEvent;
use App\Events\Contract\onUpdateEvent as onContractUpdateEvent;
use App\Events\Contract\onCloseEvent as onContractCloseEvent;
use App\Events\Tasks\onCreateEvent as onTaskCreateEvent;
use App\Events\Tasks\onUpdateEvent as onTaskUpdateEvent;

use Exception;
use App\Exceptions\WorkflowException;


class WorkflowService
{
    private $user;
    
    private $request;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var Contract
     */    
    private $contract;
    
    /**
     * @var Task
     */
    private $firstTask;
    private $nextTask;
    private $existedTask;    
    private $tmpTask;
    
    /**
     * @var Comment
     */
    private $comment;
    
    /**
     * @var int
     */
    private $contractableRoute;
    private $tariffId;
    
    private $message = '';
    
    private $commentMsg = null;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        //$this->user = User::find($this->request->user('api')->id);
        $this->user = $this->request->user('api');
    }
    
    /**
     * Create new Customer.
     * 
     * @return \App\Models\Customer
     * 
     * @throw \App\Exceptions\WorkflowException
     */
    public function createNewCustomer(): CustomerResponse
    {
//        if(empty($this->user)) {
//            $this->error = true;
//            $this->message = 'Невозможно создание нового клиента - Не найден текущий пользователь!';
//            return new CustomerResponse($this->message);
//        }
        
        try {
            $this->customer = Customer::create([
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
            if(empty($this->customer)) {                
                $this->message = 'Ошибка при создании нового клиента';
                throw new WorkflowException($this->message);
            }
        }
        catch(Exception $exception) {            
            $this->message = 'Ошибка при создании нового клиента (исключение)!';
            throw new WorkflowException($this->message);
	    }
	    
        
        try {
            $phone = Phone::create([
                'customer_id' => $this->customer->id,
                'phone' => $this->request->input('phone'),
            ]);
            if(empty($phone)) {                
                $this->message = 'Ошибка при добавлении телефонов для клиента ' 
                                . $this->customer->name . ' ' . $this->customer->surname;
                $this->deleteAndThrow();
            }
        }
        catch(Exception $exception) {            
	        $this->message = 'Ошибка при добавлении телефонов для клиента ' 
	                        . $this->customer->name . ' ' . $this->customer->surname . 
	                        ' (исключение)!';
            $this->deleteAndThrow();
	    }
        
        Event::dispatch(new onCustomerCreateEvent($this->customer));
        
        $this->contractableRoute = 1;
      
        $this->createNewContract($this->customer);
        
        $this->message = 'Новый клиент ' . $this->customer->name . ' ' . $this->customer->surname 
                        . ' успешно создан и ему автоматически добавлен новый контракт № ' . $this->contract->contract_num;
        return new CustomerResponse($this->message, $this->customer);
    }
    
    
    /**
     * Create new Contract for Customer.
     * 
     * @param  \App\Models\Customer $customer
     * 
     * @return \App\Models\Contract
     * 
     * @throw \App\Exceptions\WorkflowException
     */
    public function createNewContract(Customer $customer): ContractResponse
    {
        $this->setTariff();
        if(empty($this->tariffId)) {
            $this->message = 'Отсутствуют данные о тарифе для нового контракта для клиента ' 
                            . $customer->name . ' ' . $customer->surname;
            $this->deleteAndThrow();
        }        
                    
        $contractNum = $this->createContractNum();
        if(empty($contractNum)) {            
            $this->message = 'Ошибка выделения номера для нового контракта для клиента ' 
                            . $customer->name . ' ' . $customer->surname;
            $this->deleteAndThrow();
        }
        
        try {
            $this->contract = Contract::create([
                'contract_num' => $contractNum,
                'customer_id' => $customer->id,
                'tariff_id' => $this->tariffId,
            ]);
            if(empty($this->contract)) {                
                $this->message = 'Ошибка при создании нового контракта для клиента ' 
                                . $customer->name . ' ' . $customer->surname;
                $this->deleteAndThrow();
            }
        }
        catch(Exception $exception) {            
	        $this->message = 'Ошибка при создании нового контракта для клиента ' 
	                        . $customer->name . ' ' . $customer->surname 
                            . ' (исключение)';
            $this->deleteAndThrow();
	    }
        
        Event::dispatch(new onContractCreateEvent($this->contract));
        
        $this->contractableRoute = 1;

        $this->setComment();
                
        $this->createFirstTask();
        
        $this->message = 'Успешно создан новый контракт № ' . $this->contract->contract_num 
                        . ' для клиента ' . $customer->name . ' ' . $customer->surname;
        return new ContractResponse($this->message, $this->contract);        
    }
    
    /**
     * Create first Task.
     * 
     * @return \App\Models\Task
     * 
     * @throw \App\Exceptions\WorkflowException
     */
    public function createFirstTask(): TaskResponse
    {
        //После валидации будет излишним
        //if($this->request->input('route') != 1) {
        //    return null;
        //}
        //Првоерка на единичность задач по контракту с роутом = 1
        if($this->contractableRoute) {
            $route = Route::where('value', $this->contractableRoute)->first();
            $contractableTask = 1;
        }
        else {
            $route = Route::where('value', $this->request->input('route'))->first();
            $contractableTask = null;
        }
        if(empty($route)) {
            $this->message = 'Ошибка при создании задачи - нет информации о маршруте!';
            $this->deleteAndThrow();
        }
        
        $firstProcessSequence = 1; //Нужен метод в модели поиска первого процесса (с минимальным sequence)
        $process = Process::getProcess($route, $firstProcessSequence);
        if(empty($process)) {
            $this->message = 'Стартовый процесс в выбранном маршруте обработки задач не найден!';
            $this->deleteAndThrow();
        }
        
        if($this->contract) {
            $contractId = $this->contract->id;
            $tariffId = $this->contract->tariff_id;
        }
        else {
            $contractId = $this->request->input('contract_id');            
            $tariffId = null;
        }
        if(empty($contractId)) {
            $this->message = 'Невозможно создать новую задачу - отсутствует информация о номере контракта!';
            $this->deleteAndThrow();
        }
        
        try {
            $this->firstTask = Task::create([
                'task' => $this->createTaskNum(),
                'task_sequence' => 1,
                'route' => $route->value,
                'process_sequence' => $firstProcessSequence,
                'is_contractable' => $contractableTask,
                'title' => $route->title,
                //'description' => $this->request->input('description'),
                'status' => 1,
                'process_id' => $process->id,
                'contract_id' => $contractId,
                'tariff_id' => $tariffId,
                'creating_user_id' => $this->user->id,                
                'creating_user_name' => $this->user->name,
                'creating_user_email' => $this->user->email,
                'deadline' => \Carbon\Carbon::now()->format('dmyHi')
            ]);
            if(empty($this->firstTask)) {                
                $this->message = 'Ошибка БД при создании новой задачи!';
                $this->deleteAndThrow();
            }
        }
        catch(Exception $exception) {            
	        $this->message = 'Ошибка БД при создании новой задачи (исключение)!';
	        $this->deleteAndThrow();
	    }        
              
        if(isset($this->commentMsg)) {
            $this->createComment();
        }
        else {
            if($this->setComment()) {
                $this->createComment();
            }
        }
        
        $this->closeTask();        
                
        /** @var App\Models\Process $nextProcess */
        $nextProcess = $this->firstTask->process->getNextProcess($route->id); //настройка работы исключений - убрать id!!!
        if(empty($nextProcess)) {            
            $this->message = 'Не найден следующий процесс обработки данной задачи!';
            $this->deleteAndThrow();
        }
        
        try {
            $this->nextTask = Task::create([
                'task' => $this->firstTask->task,
                'task_sequence' => $this->createTaskSeq(),
                'route' => $this->firstTask->route,
                'process_sequence' => $nextProcess->sequence,
                'is_contractable' => $this->firstTask->is_contractable,
                'title' => $this->firstTask->title,
                //'description' => $this->request->description,
                'status' => 1,
                'process_id' => $nextProcess->id,
                'contract_id' => $this->firstTask->contract_id,
                'tariff_id' => $this->firstTask->tariff_id,
                'creating_user_id' => $this->user->id,                                
                'creating_user_name' => $this->user->name,
                'creating_user_email' => $this->user->email,
                'deadline' => \Carbon\Carbon::now()->format('dmyHi')
            ]);
            if(empty($this->nextTask)) {
                $this->message = 'Ошибка БД при создании следующего процесса "' 
                                . $nextProcess->title . '" для задачи № ' 
                                . $this->firstTask->task;
                $this->deleteAndThrow();
            }
        }
        catch(Exception $exception) {
            $this->message = 'Ошибка БД при создании следующего процесса "' 
	                        . $nextProcess->title . '" для задачи № ' 
	                        . $this->firstTask->task . ' (Исключение)';
            $this->deleteAndThrow();
	    }
	    
	    Event::dispatch(new onTaskCreateEvent($this->nextTask));
	    
        $this->message = 'Новая задача № ' . $this->nextTask->task 
                        . ' успешно создана и передана в следующий процесс обработки "' 
                        . $this->nextTask->process_name . '"';
        return new TaskResponse($this->message, $this->nextTask);
    }
    
    
    /**
     * Create next Task.
     * 
     * @return \App\Services\TaskResponse
     */
    public function createNextTask(
                                   Task $task,
                                   Process $process
                                ): TaskResponse
    {
        $this->existedTask = $task;        
        
        if(!$this->setComment()) {
            $this->message = 'Отсутствует текст обязательного коментария к задаче № ' . $this->existedTask . ' !';
            throw new WorkflowException($this->message);
        }        

        $this->createComment();        

        //Закрытие предидущего или последнего sequence
        $this->closeTask();
                
        if($this->request->input('destination') == 3) {
            $this->message = 'Задача № ' . $this->existedTask->task 
                            . ' успешно завершена и закрыта';
            return new TaskResponse($this->message, $this->existedTask);
        }
        
        if($this->existedTask->is_contractable) {
            $currentTariffId = Contract::find($this->existedTask->contract_id)->tariff_id;
        }
        else {
            $currentTariffId = null;
        }
        
        try {
            $this->nextTask = Task::create([
                'task' => $this->existedTask->task,
                'task_sequence' => $this->createTaskSeq(),
                'route' => $this->existedTask->route,
                'process_sequence' => $process->sequence,
                'is_contractable' => $this->existedTask->is_contractable,
                'title' => $this->existedTask->title,                
                'status' => 1,
                'process_id' => $process->id,
                'contract_id' =>$this->existedTask->contract_id,
                'tariff_id' => $currentTariffId,
                'creating_user_id' => $this->user->id,                                
                'creating_user_name' => $this->user->name,
                'creating_user_email' => $this->user->email,
                'deadline' => \Carbon\Carbon::now()->format('dmyHi')
            ]);
            if(empty($this->nextTask)) {
                switch ($this->request->input('destination')) {
                    case 1:
                        $this->message = 'Ошибка БД при создании следующего процесса "' 
                                        . $process->title . '" для задачи № ' 
                                        . $this->existedTask->task;
                        throw new WorkflowException($this->message);
                        break;
                    case 2:
                        $this->message = 'Ошибка БД при возврате в предидущий процесс "' 
                                        . $process->title . '" для задачи № ' 
                                        . $this->existedTask->task;
                        throw new WorkflowException($this->message);
                        break;
                    default:
                        $this->message = 'Ошибка БД при создании процесса "' 
                                        . $process->title . '" для задачи № ' 
                                        . $this->existedTask->task;
                        throw new WorkflowException($this->message);
                }
            }
        }
        catch(Exception $exception) {	        
	        $this->message = 'Ошибка БД при создании процесса "' . $process->title 
	                        . '" для задачи № ' . $this->existedTask->task;
            throw new WorkflowException($this->message);
	    }
        
        Event::dispatch(new onTaskUpdateEvent($this->existedTask, $this->nextTask));        
        
        switch ($this->request->input('destination')) {
            case 1:
                $this->message = 'Процесс "' . $this->existedTask->process_name 
                                . '" по задаче № ' . $this->nextTask->task 
                                . ' успешно выполнен и задача передана в следующий процесс обработки "' 
                                . $process->title . '"';
                return new TaskResponse($this->message, $this->nextTask);
            case 2:
                $this->message = 'Процесс "' . $this->existedTask->process_name 
                                . '" по задаче № ' . $this->nextTask->task 
                                . ' успешно выполнен и задача возвращена в предидущий процесс обработки "' 
                                . $process->title . '"';
                return new TaskResponse($this->message, $this->nextTask);
            default:
                $this->message = 'Процесс "' . $this->existedTask->process_name 
                                . '" по задаче № ' . $this->nextTask->task 
                                . ' успешно выполнен и задача передана в процесс обработки "' 
                                . $process->title . '"';
                return new TaskResponse($this->message, $this->nextTask);
        }
    }
    
    /**
     * Create Comment.
     * 
     * @return \App\Services\TaskResponse
     */
    public function createComment(Task $task = null): CommentResponse
    {
        if($task) {
            $this->existedTask = $task;
        }
        
        $this->defineTmpTask();
        
        if(empty($this->commentMsg)) {
            $this->setComment();
        }
        
        try {
            $this->comment = Comment::create([
                'task_num' => $this->tmpTask->task,
                'task_seq_num' => $this->tmpTask->task_sequence,
                'task_id' => $this->tmpTask->id,
                'comment_seq' => $this->createCommentSeq(),
                'common_comment_seq' => $this->createCommonCommentSeq(),
                'comment' => $this->commentMsg,
                'user_id' => $this->user->id,
                'user_name' => $this->user->name,
                'user_email' => $this->user->email
            ]);
            if(empty($this->comment)) {                
                $this->message = 'Ошибка при добавлении комментария к задаче № ' 
                                . $this->tmpTask->task;
                $this->deleteAndThrow();
            }
        }
        catch(Exception $exception) {            
	        $this->message = 'Ошибка (исключение) при добавлении комментария к задаче № ' 
                                . $this->tmpTask->task;
            $this->deleteAndThrow();
	    }
	    
	    $this->message = 'Комментарий к процессу "' . $this->tmpTask->process->title 
                                . '" задачи № ' . $this->tmpTask->task 
                                . ' успешно сохранен';
        return new CommentResponse($this->message, $this->comment);
	    
    }
    
    
    
    //--------------------------------------------------//
    private function createContractNum(): ?int 
    {
        $lastContract = Contract::all()->max('contract_num');
            return $lastContract + 1 ?? null;
    }
    
    private function closeTask() 
    {           
        $this->defineTmpTask();        
        
        try {            
            //Закрытие предидущего или последнего sequence
            $this->tmpTask->status = 0;
            $this->tmpTask->closing_user_id = $this->user->id;
            $this->tmpTask->closing_user_name = $this->user->name;
            $this->tmpTask->closing_user_email = $this->user->email;
            if(!$this->tmpTask->save()) {                
                $this->message = 'Ошибка БД при закрытии процесса "' 
                                . $this->tmpTask->process->title . '" по задаче № '
                                . $this->tmpTask->task;
                                ;
                $this->deleteAndThrow();
            }        
        }
        catch(Exception $exception) {            
	        $this->message = 'Ошибка БД при закрытии процесса "' . $this->tmpTask->process->title 
                            . '" по задаче № '. $this->tmpTask->task . ' (Исключение)';
            $this->deleteAndThrow();
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
        $this->defineTmpTask();
        return $this->tmpTask->task_sequence + 1;
    }
    
    private function defineTmpTask()
    {
        if(is_object($this->firstTask) && !is_object($this->existedTask)) {
            $this->tmpTask = $this->firstTask;
        }
        else if(!is_object($this->firstTask) && is_object($this->existedTask)) {
            $this->tmpTask = $this->existedTask;
        }
        else if(!is_object($this->firstTask) && !is_object($this->existedTask)) {
            $this->message = 'Ошибка при закрытии задачи - не найдена предидущая задача!';
            $this->deleteAndThrow();
        }
        else if(is_object($this->firstTask) && is_object($this->existedTask)) {
            $this->message = 'Ошибка при закрытии задвчи - не определена однозначно предидущая задача!';
            $this->deleteAndThrow();
        }
    }
    
    
    private function createCommentSeq(): int 
    {
        return $this->tmpTask->comments->count() + 1;
    }
    
    
    private function createCommonCommentSeq(): int
    {
        return Comment::getAllCommentsForTask($this->tmpTask)->count() + 1;
    }
    
    private function setComment(): bool
    {
        if($this->request->has('task_comment')) {
            $this->commentMsg = $this->request->input('task_comment');
            return true;
        }
        if($this->request->has('comment')) {
            $this->commentMsg = $this->request->input('comment');
            return true;
        }
        return false;
    }

    private function setTariff() {
        if($this->request->has('contract_tariff_id') && !$this->request->has('tariff_id')) {
            //$this->request->request->add(['tariff_id' => $this->request->input('contract_tariff_id')]);
            $this->tariffId = $this->request->input('contract_tariff_id');
        }
        else if (!$this->request->has('contract_tariff_id') && $this->request->has('tariff_id')) {
            $this->tariffId = $this->request->input('tariff_id');
        }
        else if ($this->request->has('contract_tariff_id') && $this->request->has('tariff_id')) {            
            $this->message = 'Ошибка однозначного определения типа контракта для клиента (первый или последующий)!';
            $this->deleteAndThrow();
        }
        else if (!$this->request->has('contract_tariff_id') && !$this->request->has('tariff_id')) {            
            $this->message = 'Отсутствуют данные о тарифе для подключаемого клиента!';
            $this->deleteAndThrow();
        }
    }

    private function deleteAndThrow() {
        if(is_object($this->nextTask)) {
            $this->nextTask->delete();
        }
        
        //потом убрать!!
        if(is_object($this->comment)) {
            $this->comment->delete();
        }
        
        if(is_object($this->firstTask)) {
            $this->firstTask->delete();
        }
        if(is_object($this->contract)) {
            $this->contract->delete();
        }
        if(is_object($this->customer)) {
            $this->customer->delete();
        }          
        throw new WorkflowException($this->message);
    }
}