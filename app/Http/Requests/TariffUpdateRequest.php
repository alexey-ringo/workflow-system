<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;


class TariffUpdateRequest extends FormRequest
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
            'description' => 'required|string|max:100',
        ];
    }
    
    public function messages()
    {
        return [
            'description.required' => 'Описание тарифа должно быть заполнено!',
            'description.max' => 'Описание тарифа не должно превышать длины 100 символов!',
            
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            new JsonResponse($validator->messages(), 422)
        );
    }
}
