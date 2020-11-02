<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('halodoc')->accessToken;
            return response()->json(
                [
                    'success' => true,
                    'data'=> $success
                ], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->roles()->attach(Role::where('slug', 'patient')->first());
        $success['token'] =  $user->createToken('halodoc')->accessToken;
        $success['name'] =  $user->name;

        return response()->json([
            'success'=>true,
            'data'=> $success
        ], $this->successStatus);
    }

    public function detail()
    {
        $user = Auth::user();
        $user['roles'] = $user->roles()->select(['name', 'slug'])->get();
        return response()->json([
            'success' => true,
            'data' => $user
        ], $this->successStatus);
    }
}
