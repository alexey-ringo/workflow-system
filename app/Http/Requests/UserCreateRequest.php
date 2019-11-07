<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UserCreateRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'phone' => 'digits:11|unique:users',
            'email' => 'required|string|email|max:100',
            'password' => 'required|confirmed|min:6',
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Имя пользователя должно быть заполнено!',
            'name.max' => 'Имя пользователя не должно быть длинее, чем 100 знаков!',
            'phone.digits' => 'Телефонный номер должен содержать 11 цифр и начинаться с цифры 7!',
            'phone.unique' => 'Пользователь с таким телефонным номером уже есть в базе данных системы!',
            'email.required' => 'email пользователя должно быть указан!',
            'email.email' => 'Email должен быть как email!',
            'password.required' => 'У пользователя должен быть заполнен пароль!',
            'password.confirmed' => 'Пароль пользователя должен быть подтвержден!',
            'password.min' => 'Пароль пользователя должен состоять не менее чем из 6 символов!',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            new JsonResponse($validator->messages(), 422)
        );
    }
}
