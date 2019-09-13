<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

use App\Services\CustomerService;
use App\Http\Resources\Customer\CustomerCollection;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\CustomerRelationResource;
use App\Http\Resources\Customer\CustomerCustomResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new CustomerCollection(Customer::with('phones')->get());
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CustomerService $customerService)
    {
        $currentUser = User::find($request->user('api')->id);
        if(!$currentUser) {
            return response()->json(['data' => [
                                        'error' => true,
                                        'message' => 'Не определен пользователь, создающий нового клиента'
                                    ]]);
        }
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:processes',
            'sequence' => 'required|unique:processes',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        /** @var App\Services\CustomerResponse $createdCustomer */
        $createdCustomer = $customerService->createNewCustomer($request, $currentUser);
        
        return response()->json(['data' => [
                                        'error' => $createdCustomer->getError(),
                                        'customer' => $createdCustomer->getCustomer(),
                                        'message' => $createdCustomer->getMessage()
                                    ]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(/*Customer $customer*/int $id)
    {
        $customer = Customer::find($id);
        if($customer) {
            $contracts = $customer->contracts()->get();
            $phones = $customer->phones()->get();
            return new CustomerCustomResource($customer, $phones, $contracts);
        }
        else {
            return new CustomerResource($customer);
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
    public function destroy(Customer $customer)
    {
        if($customer->delete()) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }  
    }
}
