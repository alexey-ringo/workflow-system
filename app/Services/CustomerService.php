<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Phone;
use App\Models\Customer;
use App\Models\Contract;

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
    public function createNewCustomer(Request $request): ?Customer
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
            return null;
        }
        //Нужны информативные ответы
        
        $phone = Phone::create([
            'customer_id' => $customer->id,
            'phone' => $request->get('phone'),
                ]);
        
        if(!$phone) {
            //$this->destroy($customer);
            $customer->destroy();
            return null;
        }
        //Нужны информативные ответы
        
        
        Event::dispatch(new onCustomerCreateEvent($customer));
        
        $contract = $this->createNewContract($customer);
        if(!$contract) {
            $customer->delete();
            return null;
        }
        
        return $customer;
    }
    
    
    public function createNewContract(Customer $customer): ?Contract
    {
        $contractNum = $this->createContractNum();
        if(!$contractNum) {
            return null;
        }
        $contract = Contract::create([
            'contract_num' => $contractNum,
            'customer_id' => $customer->id,
        ]);
        //Нужно поймать исключение
        //Illuminate\Database\QueryException
        
        Event::dispatch(new onContractCreateEvent($contract));
        
        return $contract;
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