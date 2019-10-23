<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use App\Exceptions\WorkflowException;

class CustomerStoreRequest extends FormRequest
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
            'city' => 'required|string|max:50',
            'street' => 'required|string|max:50',
            'building' => 'required|string|max:25',
            'flat' => 'required|string|max:25',
            'phone' => 'required|digits:11|unique:phones',
            'email' => 'required|email:rfc,dns',            
            'task_comment' => 'string|max:250',
            'contract_tariff_id' => 'required|numeric',
            'description' => 'string|max:250',
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Имя клиента должно быть заполнено!',
            'second_name.required' => 'Отчество клиента должно быть заполнено!',
            'surname.required' => 'Фамилия клиента должна быть заполнено!',
            'city.required' => 'Информация о городе проживания клиента должна быть заполнена!',
            'street.required' => 'Информация об улице проживания клиента должна быть заполнена!',
            'building.required' => 'Информация о номере дома проживания клиента должна быть заполнена!',
            'flat.required' => 'Информация о квартире проживания клиента должна быть заполнена!',
            'phone.required' => 'Телефон клиента должен быть заполнен',
            'phone.unique' => 'Телефон клиента :attribute уже был внесен в базу ранее!',
            'email.required' => 'Email клиента должно быть заполнен!',
            //'comment.max' => 'Максимальная длина коммента [:max]'
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            new JsonResponse($validator->messages(), 422)
        );
        //throw new WorkflowException(new JsonResponse($validator->messages(), 422));
        
        
        
        //if ($validator->fails()) {    
            //response()->json($validator->errors(), 200);
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
