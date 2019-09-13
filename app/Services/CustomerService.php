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
    public function createNewCustomer(Request $request, User $user): CustomerResponse
    {
        try {
            $customer = Customer::create([
                'surname' => $request->get('surname'),
                'name' => $request->get('name'),
                'second_name' => $request->get('second_name'),
                'city' => $request->get('city'),
                'region' => $request->get('region'),
                'street' => $request->get('street'),
                'building' => $request->get('building'),
                'flat' => $request->get('flat'),
                'email' => $request->get('email'),
                'description' => $request->get('description'),
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
	            report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка при создании нового клиента (исключение)!';
            return new CustomerResponse($message);
	    }
        
        try {
            $phone = Phone::create([
                'customer_id' => $customer->id,
                'phone' => $request->get('phone'),
            ]);
            if(!$phone) {
                $message = 'Ошибка при добавлении телефонов для клиента ' . $customer->name > ' ' . $customer->surname;
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
	            report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка при добавлении телефонов для клиента ' . $customer->name > ' ' . $customer->surname . ' (исключение)!';
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
	            report($exception);
            }
            else {
	            report($exception);
            }
	        $message = 'Ошибка при генерации события создания нового клиента ' . $customer->name > ' ' . $customer->surname;
            return new CustomerResponse($message);
	    }
        
        $request->request->add(['task_route' => 1]);
        $request->request->add(['task_description' => 'Первоначальная задача при создании нового клиента']);
      
        /** @var App\Services\ContractResponse $contractResponse */
        $contractResponse = $this->createNewContract($request, $customer, $user);
        if($contractResponse->getError()) {
            $message = 'Ошибка при автоматическом создании контракта для клиента ' . $customer->name . ' ' . $customer->surname . '  ' . $contractResponse->getMessage();
            $customer->delete();
            return new CustomerResponse($message);
        }
        
        $message = 'Новый клиент ' . $customer->name . ' ' . $customer->surname . ' успешно создан и ему автоматически добавлен новый контракт № ' . $contractResponse->getContractNum();
        return new CustomerResponse($message, $customer);
    }
    
    
    
    public function createNewContract(Request $request, Customer $customer, User $user): ContractResponse
    {
        $contractNum = $this->createContractNum();
        if(!$contractNum) {
            $message = 'Ошибка выделения номера для нового контракта для клиента ' . $customer->name . ' ' . $customer->surname;
            return new ContractResponse($message);
        }
        
        
        try {
            $contract = Contract::create([
                'contract_num' => $contractNum,
                'customer_id' => $customer->id,
            ]);
            if(!$contract) {
                $message = 'Ошибка при создании нового контракта для клиента ' . $customer->name . ' ' . $customer->surname;
                return new ContractResponse($message);
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
	        $message = 'Ошибка при создании нового контракта для клиента ' . $customer->name > ' ' . $customer->surname . ' (исключение)';
            return new ContractResponse($message);
	    }
        
        
        try {
            Event::dispatch(new onContractCreateEvent($contract));
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
	        $message = 'Ошибка при генерации события создания нового контракта ' . $contract->contract_num . ' для клиента ' . $customer->name . ' ' . $customer->surname;
            return new ContractResponse($message);
	    }
        
        
        /** @var App\Services\TaskResponse $taskResponse */
        $taskResponse = $this->createNewTask($request, $contract, $user);
        if($taskResponse->getError()) {
            $message = 'Ошибка при автоматическом создании новой задачи для контракта ' . $contract->contract_num . 
                        ' у клиента ' . $customer->name . ' ' . $customer->surname . ' : ' . $taskResponse->getMessage();
            $contract->delete();
            return new ContractResponse($message);
        }
        
        $message = 'Успешно создан новый контракт № ' . $contract->contract_num . ' для клиента ' . $customer->name . ' ' . $customer->surname;
        return new ContractResponse($message, $contract);
    }
    
    private function createNewTask(Request $request, Contract $contract, User $user): TaskResponse
    {
        if($request->has('task_route')) {
            $request->request->add(['route' => $request->get('task_route')]);
        }
        else {
            $message = 'Ошибка при создании задачи - нет информации о маршруте';
            return new TaskResponse($message);
        }
        
        if($request->has('task_description')) {
            $request->request->add(['description' => $request->get('task_description')]);
        }
        else {
            $message = 'Ошибка при создании задачи - в запросе отсутстует поле с описанием задачи';
            return new TaskResponse($message);
        }
       
        $request->request->add(['contract_id' => $contract->id]);
        
        return (new TaskService)->createFirstTask($request, $user);
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