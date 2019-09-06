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


class SearchController extends Controller
{
    public function searchPhone(Request $request)
    {
        $phone = Phone::where('phone', $request->keywords)->first();
        //dd($request->keywords, $phone);

        //return response()->json(['data' => $phone]);
        //return response()->json($phone);
        if($phone) {
            $customer = $phone->customer;
            //$customer = $customer->with('contracts')->first();
            return new CustomerResource($customer->with('phones', 'contracts')->first());
        }
        else {
            return response()->json(['data' => 0]);
        }
    }
    
    public function searchSurname(Request $request)
    {
        //$customer = Customer::where('surname', $request->keywords)->get();
        $customer = Customer::where('surname', 'LIKE', '%' . $request->keywords . '%')->get();
        
        if($customer) {
            return new CustomerCollection($customer);
        }
    }
}
