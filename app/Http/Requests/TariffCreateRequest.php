<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;


class TariffCreateRequest extends FormRequest
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
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'sku' => 'required|digits:7',
            'price' => 'required|integer|max:100000',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Назвение тарифа должно быть заполнено!',
            'title.max' => 'Назвение тарифа не должно превышать длины 50 символов!',
            'description.required' => 'Описание тарифа должно быть заполнено!',
            'description.max' => 'Описание тарифа не должно превышать длины 100 символов!',
            'sku.required' => 'Поле SKU тарифа должно быть заполнено!',
            'sku.digits' => 'Длина поля SKU тарифа должна быть равна 7 цифрам!',
            'price.required' => 'Цена тарифа должно быть заполнена!',
            'price.max' => 'Значение поля Цена тарифа не должна превышать значение 100 000!',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            new JsonResponse($validator->messages(), 422)
        );
    }
}
