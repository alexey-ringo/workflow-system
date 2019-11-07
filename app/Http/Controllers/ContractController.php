<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

use App\Services\WorkflowService;
use App\Http\Resources\Contract\ContractCollection;
use App\Http\Resources\Contract\ContractResource;
use App\Http\Resources\Contract\ContractRelationResource;
use Gate;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('index', Contract::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр списка контрактов!'], 422);
        }
        return new ContractCollection(Contract::with('customer', 'tariff')->get());
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //В $request передаем данные о customer
    public function store(Request $request, WorkflowService $workflowService)
    {
        if(Gate::denies('store', Contract::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на создание нового контракта!'], 422);
        }
        
        $customer = Customer::find($request->input('id'));
        
        if($customer) {
            $createdContract = $workflowService->createNewContract($customer);

            return response()->json(['message' => $createdContract->getMessage()]);
        }
        else {
            return response()->json(['message' => 'Клиент для создания нового контракта не найден!'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if(Gate::denies('show', Contract::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр данного контракта!'], 422);
        }
        
        $contract = Contract::find($id);
        if($contract) {
            return new ContractRelationResource($contract);
        }
        else {
            return response()->json(['message' => 'Контракт с идентификатором id ' . $id . ' не найден!'], 422);
        }
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        if(Gate::denies('update', Contract::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на редактирование данного контракта!'], 422);
        }
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'sequence' => 'required',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        
        
    }
}
