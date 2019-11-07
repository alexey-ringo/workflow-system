<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

use App\Services\WorkflowService;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Resources\Customer\CustomerCollection;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\CustomerRelationResource;
use App\Http\Resources\Customer\CustomerCustomResource;
use Gate;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('index', Customer::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр списка клиентов'], 422);
        }
        
        return new CustomerCollection(Customer::with('phones')->get());
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerStoreRequest $request, WorkflowService $workflowService)
    {
        if(Gate::denies('store', Customer::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на создание нового клиента'], 422);
        }
        
        /** @var App\Services\CustomerResponse $createdCustomer */
        $createdCustomer = $workflowService->createNewCustomer();
        
        return response()->json(['message' => $createdCustomer->getMessage()]);
   }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if(Gate::denies('show', Customer::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр информации о данном клиенте'], 422);
        }
        
        $customer = Customer::find($id);
        if($customer) {
            $contracts = $customer->contracts()->get();
            $phones = $customer->phones()->get();
            return new CustomerCustomResource($customer, $phones, $contracts);
        }
        else {
            return response()->json(['message' => 'Клиент с идентификатором id ' . $id . ' не найден!'], 422);
        }
        
        //return new CustomerRelationResource($customer);
        
        //return new CustomerResource($customer->with('phones', 'contracts')->first());
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        if(Gate::denies('update', Customer::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на редактирование данного клиента'], 422);
        }
        
        if($customer->update($request->only(['surname', 'name', 'second_name', 'city', 'region', 'street', 'building', 'flat', 'description']))) {
            return response()->json(['message' => 'Изменения в карточке клиента "' . $customer->name . ' ' . $customer->surname . '" успешно применены']);
        }
        else {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений в карточке клиента!'], 500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if(Gate::denies('destroy', Customer::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на удаление данного клиента'], 422);
        }
        
        if($customer->delete()) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }  
    }
}
