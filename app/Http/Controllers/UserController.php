<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserRelationResource;
use Gate;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('index', User::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр списка пользователей!'], 422);
        }
        
        return new UserCollection(User::all());
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        if(Gate::denies('store', User::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на создание нового пользователя!'], 422);
        }
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        if(empty($user)) {
            return response()->json(['message' => 'Внутренняя ошибка сервера при создании нового пользователя!'], 500);
        }
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        //return response()->json(['success' => $success]);
        return response()->json(['message' => 'Новая пользователь "' . $user->name . ' ' . $user->surname . '" успешно создан']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if(Gate::denies('show', User::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр профиля данного пользователя!'], 422);
        }
        
        $user = User::find($id);
        if($user) {
            return new UserRelationResource($user);
        }
        else {
            return response()->json(['message' => 'Пользователь с идентификатором id ' . $id . ' не найден!'], 422);
        }               
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        if(Gate::denies('update', User::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на редактирование профиля данного пользователя!'], 422);
        }
        
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        //Если поле == null - в модель пароль не передавать, иначе - зашифровать перед передачей в модель
        $request->input('password') == null ?: $user->password = bcrypt($request['password']);
        
        if(empty($user->save())) {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений данных пользователя!'], 500);
        }
        
        //Если список ролей пуст - отсоединяем
        $user->roles()->detach();
        //Проверка на наличие полученного от формы значения поля с name="roles"
        if($request->input('roles')) :
            $user->roles()->attach($request->input('roles'));
        endif;
        
        //Если список групп пуст - отсоединяем
        $user->groups()->detach();
        //Проверка на наличие полученного от формы значения поля с name="groups"
        if($request->input('groups')) :
            $user->groups()->attach($request->input('groups'));
        endif;      
        
        
        return response()->json(['message' => 'Изменения для пользователя "' . $user->name . ' ' . $user->surname . '" успешно применены']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(Gate::denies('destroy', User::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на удаление данного пользователя!'], 422);
        }
    
        //Какой то непонятной магией Laravel восстанавливает объект $user из пришедшего в метод id !!!
        //$user = User::find($id);
        
        $user->roles()->detach();
        $user->groups()->detach();
        $user->delete();

        return new UserCollection(User::all());
    }
}
