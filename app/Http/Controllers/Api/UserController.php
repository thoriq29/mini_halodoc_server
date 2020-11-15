<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use Validator;

use App\Models\Auth\Role;
use App\Models\Notification;
use App\Models\FcmToken;

use File;

class UserController extends Controller
{
    public $successStatus = 200;

    public function validate_token()
    {
        if(!Auth::user()) {
            return response()->json([
                'success'=>true,
                'data'=> "Token tidak valid"
            ], 403);
        }
        return response()->json([
            'success'=>true,
            'data'=> "Aunthenticated"
        ], $this->successStatus);
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            if($request->token != null) {
                $checkToken = FcmToken::where('token', $request->token)->first();
                if(!isset($checkToken)) {
                    $user->fcmTokens()->create([
                        'token'=> $request->token
                    ]);   
                }
            }
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
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone' => 'required',
            'sex' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $user->patient()->create([
            'user_id' => $user->id,
            'address' => "",
            "phone" => $input['phone'],
            "sex" => $input['sex'],
            'image_url' => $input['image_url']
        ]);



        $user->roles()->attach(Role::where('slug', 'patient')->first());
        $success['token'] =  $user->createToken('halodoc')->accessToken;
        $success['name'] =  $user->name;
        if($request->token != null) {
            $checkToken = FcmToken::where('token', $request->token)->first();
            if(!isset($checkToken)) {
                $user->fcmTokens()->create([
                    'token'=> $request->token
                ]);   
            }
        }
        return response()->json([
            'success'=>true,
            'data'=> $success
        ], $this->successStatus);
    }

    public function detail()
    {
        $user = Auth::user();
        $user['roles'] = $user->roles()->select(['name', 'slug'])->get();
        $user['isDoctor'] = false;
        $user['isPatient'] = false;

        if($user->isDoctor()) {
            $user['isDoctor'] = true;
        } 

        if($user->isPatient()) {
            $user['isPatient'] = true;
            $user['sex'] = $user->patient()->first()->sex;
            $user['phone'] = $user->patient()->first()->phone;
            $user['image_url'] = $user->patient()->first()->image_url;
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], $this->successStatus);
    }

    public function upload_patient_image(Request $request)
    {
        $this->validate($request, [
			'file' => 'file|image|mimes:jpeg,jpg,png',
        ]);
        $user = Auth::user();
        if(!$user->isPatient()) {
            return response()->json([
                'success' => false,
                'data' => "Anda bukan pasien"
            ], $this->successStatus);
        }
		// menyimpan data file yang diupload ke variabel $file
        $file = $request->file('file');
        // nama folder tempat kemana file diupload
        $upload_destination = 'media';
        // upload file
        $file->move($upload_destination,$file->getClientOriginalName());
        $data = $request->all();
        $data['image_url'] = $file->getClientOriginalName();

        $user->patient()->update($data);
        return response()->json([
            'success' => true,
            'data' => $user->patient
        ], $this->successStatus);
    }

    public function getUserNotifications(Request $request)
    {
        $user = Auth::user();
        $user['notifications'] = $user->notifications;
        return response()->json([
            'success' => true,
            'data' => $user
        ], $this->successStatus);
    }

    public function notifDetail($id)
    {
        $notif = Notification::find($id);
        return response()->json([
            'success' => true,
            'data' => $notif
        ], $this->successStatus);
    }

    public function updateReadNotif($id)
    {
        $notif = Notification::find($id);
        $notif['hasRead'] = 1;
        $notif->save();
        return response()->json([
            'success' => true,
            'data' => $notif
        ], $this->successStatus);
    }

}

