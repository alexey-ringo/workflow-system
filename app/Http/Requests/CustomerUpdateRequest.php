<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use App\Models\Customer;

class CustomerUpdateRequest extends FormRequest
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
        $customer = Customer::find($this->input('id'));
        
        return [
            'name' => 'required|string|max:50',
            'second_name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'street' => 'required|string|max:50',
            'building' => 'required|string|max:25',
            'flat' => 'required|string|max:25',
            'phone' => [
                'required',
                'digits:11',
                \Illuminate\Validation\Rule::unique('phones')->ignore($customer->id),
                ],
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
            'email.required' => 'Email клиента должно быть заполнен!',
            //'comment.max' => 'Максимальная длина коммента [:max]'
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            new JsonResponse($validator->messages(), 422)
        );
    }
}
