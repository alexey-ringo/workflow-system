<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;


class ProcessRequest extends FormRequest
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
            'route_id' => 'required|integer',
            'title' => 'required|string|max:100',
            'deadline' => 'required|integer|min:1|max:168',
            'sequence' => 'required|integer|min:1|max:99',
            //'is_active' => 'integer|min:0|max:1',
        ];
    }
    
    public function messages()
    {
        return [
            'route_id.required' => 'Маршрут должен быть выбран!',
            'title.required' => 'Назвение процесса должно быть заполнено!',
            'deadline.required' => 'Значение норматива времени должно быть заполнено!',
            'deadline.integer' => 'Значение норматива времени должно быть цифровым!',
            'deadline.min' => 'Значение очереди выполнения процесса не должно быть меньше 1!',
            'deadline.max' => 'Значение очереди выполнения процесса не должно превышать 99!',
            'sequence.required' => 'Значение очереди выполнения процесса должно быть заполнено!',
            'sequence.integer' => 'Значение очереди выполнения процесса должно быть цифровым!',
            'sequence.min' => 'Значение очереди выполнения процесса не должно быть меньше 1!',
            'sequence.max' => 'Значение очереди выполнения процесса не должно превышать 99!',
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
