<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Phone;
use App\Models\Customer;
use App\Models\Contract;
use App\Models\User;

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

use Exception;
use ErrorException;
use App\Exceptions\WorkflowException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class CustomerService
{
    private $user;
    
    private $request;
    
    private $contract;
    
    private $taskService;
    
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
                $message = 'Ошибка при создании нового клиента';
                return new CustomerResponse($message);
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
	        $message = 'Ошибка при создании нового клиента (исключение)!';
            return new CustomerResponse($message);
	    }
        
        try {
            $phone = Phone::create([
                'customer_id' => $customer->id,
                'phone' => $this->request->input('phone'),
            ]);
            if(!$phone) {
                $message = 'Ошибка при добавлении телефонов для клиента ' . $customer->name . ' ' . $customer->surname;
                $customer->delete();
                return new CustomerResponse($message);
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
	        $message = 'Ошибка при добавлении телефонов для клиента ' . $customer->name . ' ' . $customer->surname . ' (исключение)!';
	        $customer->delete();
            return new CustomerResponse($message);
	    }
        
        try {
            Event::dispatch(new onCustomerCreateEvent($customer));
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
	        $message = 'Ошибка при генерации события создания нового клиента ' . $customer->name . ' ' . $customer->surname;
            return new CustomerResponse($message);
	    }
        
        $this->request->request->add(['task_route' => 1]);
        $this->request->request->add(['task_description' => 'Первоначальная задача при создании нового клиента']);
      
        /** @var App\Services\ContractResponse $contractResponse */
        $contractResponse = $this->createNewContract($customer);
        if($contractResponse->getError()) {
            $this->error = true;
            $this->message = 'Ошибка при автоматическом создании контракта для клиента ' . $customer->name . ' ' . $customer->surname . '  ' . $contractResponse->getMessage();
            $customer->delete();
            return new CustomerResponse($this->message);
        }
        
        $this->message = 'Новый клиент ' . $customer->name . ' ' . $customer->surname . ' успешно создан и ему автоматически добавлен новый контракт № ' . $contractResponse->getContractNum();
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
        
        /** @var App\Services\TaskResponse $taskResponse */
        $taskResponse = $this->createNewTask();
        if($taskResponse->getError()) {
            $this->error = true;
            $this->message = 'Ошибка при автоматическом создании новой задачи для контракта ' . $this->contract->contract_num . 
                        ' у клиента ' . $customer->name . ' ' . $customer->surname . ' : ' . $taskResponse->getMessage();
            $this->contract->delete();
            return new ContractResponse($this->message);
        }
        
        $message = 'Успешно создан новый контракт № ' . $this->contract->contract_num . ' для клиента ' . $customer->name . ' ' . $customer->surname;
        return new ContractResponse($message, $this->contract);
    }
    
    private function createNewTask(): TaskResponse
    {
        if($this->request->has('task_route')) {
            $this->request->request->add(['route' => $this->request->input('task_route')]);
        }
        else {
            $message = 'Ошибка при создании задачи - нет информации о маршруте';
            return new TaskResponse($message);
        }
        
        if($this->request->request->has('task_description')) {
            $this->request->request->add(['description' => $this->request->input('task_description')]);
        }
        else {
            $this->error = false;
            $this->message = 'Ошибка при создании задачи - в запросе отсутстует поле с описанием задачи';
            return new TaskResponse($this->message);
        }
       
        $this->request->request->add(['contract_id' => $this->contract->id]);
        
        //return (new TaskService($this->request))->createFirstTask();
        return $this->taskService->createFirstTask();
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
}