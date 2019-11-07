<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User;


class UserUpdateRequest extends FormRequest
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
        $user = User::find($this->input('id'));
        
        return [
            'name' => 'required|string|max:100',
            //Игнор валидации на уникальность для данной операции и для данного пользователя
            'phone' => [
            //    'digits:11',
                \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
                ],
            //Игнор валидации на уникальность для данной операции и для данного пользователя
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                //'users' - таблица
                \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
            ],
            //пароль при редактировании можем не менять
            'password' => 'nullable|string|min:6|confirmed',
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
