<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Phone;
use App\Models\Customer;
use App\Models\Contract;
use App\Models\User;

use App\Services\CustomerResponse;
use App\Services\ContractResponse;

use Event;
use App\Events\Customer\onCreateEvent as onCustomerCreateEvent;
use App\Events\Customer\onUpdateEvent as onCustomerUpdateEvent;
use App\Events\Contract\onCreateEvent as onContractCreateEvent;
use App\Events\Contract\onUpdateEvent as onContractUpdateEvent;
use App\Events\Contract\onCloseEvent as onContractCloseEvent;

use Exception;
use ErrorException;
use App\Exceptions\WorkflowException;

class CustomerService
{
    public function createNewCustomer(Request $request): CustomerResponse
    {
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
        //Нужно поймать исключение
        //Illuminate\Database\QueryException
        
        if(!$customer) {
            $message = 'Ошибка при создании нового клиента';
            return new CustomerResponse($message);
        }
        //Нужны информативные ответы
        
        $phone = Phone::create([
            'customer_id' => $customer->id,
            'phone' => $request->get('phone'),
                ]);
        
        if(!$phone) {
            $message = 'Ошибка при добавлении телефонов для клиента ' . $customer->name > ' ' . $customer->surname;
            $customer->delete();
            return new CustomerResponse($message);
        }
        
        Event::dispatch(new onCustomerCreateEvent($customer));
        
        /** @var App\Services\ContractResponse $newContract */
        $newContract = $this->createNewContract($customer);
        if($newContract->getError()) {
            $customer->delete();
            $message = 'Ошибка при автоматическом создании контракта для клиента ' . $customer->getCustomerName() . ' ' . $customer->getCustomerSurname();
            return new CustomerResponse($message);
        }
        
        $message = 'Новый клиент ' . $customer->name . ' ' . $customer->surname . ' успешно создан и ему автоматически добавлен новый контракт № ' . $newContract->getContractNum();
        return new CustomerResponse($message, $customer);
    }
    
    
    public function createNewContract(Customer $customer): ContractResponse
    {
        $contractNum = $this->createContractNum();
        if(!$contractNum) {
            $message = 'Ошибка при создании нового контракта для клиента ' . $customer->name . ' ' . $customer->surname . ' - ошибка выделения номера для нового контракта';
            return new ContractResponse($message);
        }
        $contract = Contract::create([
            'contract_num' => $contractNum,
            'customer_id' => $customer->id,
        ]);
        //Нужно поймать исключение
        //Illuminate\Database\QueryException
        if(!$contract) {
            $message = 'Ошибка при создании нового контракта для клиента ' . $customer->name . ' ' . $customer->surname;
            return new ContractResponse($message);
        }
        
        Event::dispatch(new onContractCreateEvent($contract));
        
        $message = 'Успешно создан новый контракт № ' . $contract->contract_num . ' для клиента ' . $customer->name . ' ' . $customer->surname;
        return new ContractResponse($message, $contract);
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