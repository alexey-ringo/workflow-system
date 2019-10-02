<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:25',
            'second_name' => 'required|string|max:25',
            'surname' => 'required|string|max:25',
            'city' => 'required|string|max:25',
            'street' => 'required|string|max:25',
            'building' => 'required|string|max:25',
            'flat' => 'required|string|max:25',
            'phone' => 'required|digits:11',
            'email' => 'required|email:rfc,dns',
            'comment' => 'required|string|max:100',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        //throw new HttpResponseException(
        //    new JsonResponse($validator->->messages(), 200)
        //);
        //if ($validator->fails()) {    
            response()->json($validator->errors(), 200);
        //}
        /*
        if ($this->wantsJson()) {
            // flatten all the message
            $collection  = collect($validator->errors())->flatten()->values()->all();
            throw new HttpResponseException(Response::error('Validation Error', $collection));
        }

        parent::failedValidation($validator);
        */
    }
}
