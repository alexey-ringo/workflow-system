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
        return new ContractCollection(Contract::with('customer', 'price')->get());
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
        /*
        $currentUser = User::find($request->user('api')->id);
        if(!$currentUser) {
            return response()->json(['data' => [
                                        'error' => true,
                                        'message' => 'Не определен пользователь, создающий новый контракт'
                                    ]]);
        }
        */
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:processes',
            'sequence' => 'required|unique:processes',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        
        $customer = Customer::find($request->input('id'));
        if($customer) {
            $newContract = $workflowService->createNewContract($customer);
            return response()->json(['data' => [
                                        'error' => $newContract->hasError(),
                                        'contract' => $newContract->getContract(),
                                        'message' => $newContract->getMessage()
                                    ]]);
        }
        else {
            return response()->json(['data' => [
                                        'error' => true,
                                        'contract' => null,
                                        'message' => 'Клиент для создания нового контракта не найден'
                                    ]]);
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
            return new ContractResource($contract);
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
