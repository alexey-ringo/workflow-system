<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Phone;
use App\Models\Task;
use App\Http\Resources\Customer\PhoneCollection;
use App\Http\Resources\Customer\PhoneResource;
use App\Http\Resources\Customer\CustomerCollection;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\CustomerRelationResource;


class SearchController extends Controller
{
    public function searchPhone(Request $request)
    {
        $phone = Phone::where('phone', $request->keywords)->first();
        if($phone) {
            $customer = $phone->customer;
            //return new CustomerResource($customer->with('phones', 'contracts')->first());
            return new CustomerRelationResource($customer);
        }
        else {
            return response()->json(['data' => 0]);
        }
    }
    
    public function searchSurname(Request $request)
    {
        //$customer = Customer::where('surname', $request->keywords)->get();
        $customers = Customer::where('surname', 'LIKE', '%' . $request->keywords . '%')->with('contracts')->get();
        if($customers->isEmpty()) {
            return response()->json(['data' => 0]);
        }
        else {
            return new CustomerCollection($customers);
        }
    }
}
