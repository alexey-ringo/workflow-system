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

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(/*Contract $contract*/int $id)
    {
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
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'sequence' => 'required',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        if($contract->delete()) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }  
    }
}
