<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserRelationResource;

use Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new UserCollection(User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success' => $success]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserRelationResource($user);
        //return response()->json(['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            //Игнор валидации на уникальность для данной операции и для данного пользователя
            'phone' => [
                'required',
                \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
                ],
            //Игнор валидации на уникальность для данной операции и для данного пользователя
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                //'users' - таблица
                \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
            ],
            //пароль при редактировании можем не менять
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        //Если поле == null - в модель пароль не передавать, иначе - зашифровать перед передачей в модель
        $request->input('password') == null ?: $user->password = bcrypt($request['password']);
        $user->save();
        
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
        
        
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //Какой то непонятной магией Laravel восстанавливает объект $user из пришедшего в метод id !!!
        //$user = User::find($id);
        
        $article->roles()->detach();
        $article->groups()->detach();
        $user->delete();

        return new UserCollection(User::all());
        //return response()->json('successfully deleted');
    }
}
