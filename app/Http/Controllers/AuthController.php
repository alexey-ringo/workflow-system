<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Auth;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //public function __construct()
    //{
    //    $this->middleware('auth');
    //}

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            //'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        $success['token'] = $request->user()->createToken('MyApp')->accessToken;
        //$user = Auth::guard('admin-api')->user();
        //$success['token'] = $user->createToken('MyApp')->accessToken;
        return response()->json(['success' => $success]);
       
    }

    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
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
     * Logout admin (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        //$request->guard('admin-api')->user()->token()->revoke();
        //Auth::guard('admin')->user()->token()->revoke();
        $user->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    
    public function currentUser() {
        $user = $request->user();
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['user'] = $user->name;
        return response()->json(['success' => $success]);
    }
}
