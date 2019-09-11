<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Http\Request;

use App\Services\CustomerService;
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
    public function store(Request $request, CustomerService $customerService)
    {
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:processes',
            'sequence' => 'required|unique:processes',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        
        $customer = Customer::find($request->get('id'));
        if($customer) {
            $newContract = $customerService->createNewContract($customer);
            return response()->json(['data' => [
                                        'error' => $newContract->getError(),
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
        $customer->surname = $request->get('surname');
        $customer->name = $request->get('name');
        $customer->second_name = $request->get('second_name');
        $customer->city = $request->get('city');
        $customer->region = $request->get('region');
        $customer->street = $request->get('street');
        $customer->building = $request->get('building');
        $customer->flat = $request->get('flat');
        $customer->description = $request->get('description');
        $customer->save();
        
        //$process->update($request->all());
        
        if($customer) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }
        
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
