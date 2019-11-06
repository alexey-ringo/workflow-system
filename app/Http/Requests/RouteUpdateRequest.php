<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Route;




class RouteUpdateRequest extends FormRequest
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
        $route = Route::find($this->input('id'));
        
        return [
            'title' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('routes')->ignore($route->id),
                ],
            'value' => 'required|integer|min:1|max:99',
            'description' => 'required|string|max:250',
            //'is_active' => 'integer|min:0|max:1',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Назвение маршрута должно быть заполнено!',
            'value.required' => 'Идентификатор маршрута должен быть указан!',
            'value.integer' => 'Значение идентификатора маршрута должно быть цифровым!',
            'value.min' => 'Значение идентификатора маршрута не должно быть меньше 1!',
            'value.max' => 'Значение идентификатора маршрута не должно превышать 99!',
            'description.required' => 'Описание маршрута должна быть заполнено!',
            //'is_active.integer' => 'Ошибка параметра Статус!',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            new JsonResponse($validator->messages(), 422)
        );
    }
}
