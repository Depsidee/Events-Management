<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController

{
   public function Register(Request $request)


   {   $input = $request->all();
        $validator =Validator::make( $input,[

            'user_name' => ['required', 'string', 'max:255' ],
            'phone_number' => ['required','unique:users' ,'digits:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
            'role_name' => ['required','string'],
           'profile_image' => ['image'],
        ]);
        $role = Role::where('name' , $input['role_name'])->first();
        if(is_null($role) ){
           return $this->sendError('Sorry, we dont have this role, please validate your role name' ,);
        }
        if($validator->fails()){
            return $this->sendError('Validate your data' , $validator->errors());
        }

        if ($request->has('profile_image')){
           $profile_image = $request->profile_image;
           $newPhoto = time().$profile_image->getClientOriginalName();
           $profile_image->move('storage/users',$newPhoto);
        }

         $user=User::create([
           'user_name'=>$request->user_name,
           'phone_number'=>$request->phone_number,
           'email'=>$request->email,
           'password'=>Hash::make('password'),
           'role_name'=>$request->role_name,
         'profile_image'=>'storage/users/'.$newPhoto ,
         ]);

         $success['token']=$user->createToken('ProgrammingLanguageProject')->accessToken;
         $success['user_name'] = $user->user_name;
         $success['phone_number'] = $user->phone_number;
         $success['email'] = $user->email;
         $success['profile_image'] = $user->profile_image;
         return $this->sendResponse($success , 'registration done successfully');
   }






   public function userLogin(Request $request){
//     $data = [
//             'email' => $request->email,
//             'phone_number' => $request->phone_number,

//         ];
//         $validator = Validator::make($request->all() , [
//             'email' => ['required'],
//             'phone_number' => ['required','unique:users' ,'digits:10']
//         ]);
//     $user=User::query()->firstWhere('email',$data['email']);
//     if(! Auth::check($data['phone_number'],$user['phone_number'])){
//         return $this->sendError('Unauthorized' , ['the phone_number is wrong']);}
//     $token=$user->createToken(env('TOKEN_SECRET'))->plainTextToken;
//     return $this->success([
// // 'token'=>$token,
// 'user'=>UserResource::make($user)

    // ]);
    $data = [
        'email' => $request->email,
        'password' => $request->password,

    ];
    $validator = Validator::make($request->all(), [
        'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
        'password' => ['required', 'min:8'],

    ]);

    if (Auth::attempt($data)) {
        $user = Auth::user();

        $success['token'] = $user->createToken('ProgrammingLanguageProject')->accessToken;
        $success['user_name'] = $user->user_name;
        return $this->sendResponse($success, 'login done successfully');
    } else {
        if ($validator->fails()) {
            return $this->sendError('Unauthorized', $validator->errors());
        }
        return $this->sendError('Unauthorized', ['user number or password isn\'t correct']);
    }
}


    public function logout(Request $request)
    {
        if (auth()->check()) {
            auth()->user()->token()->revoke();
            $success = 200;
            return $this->sendResponse($success, [
                'message' => 'Successfully logged out'
            ]);
        } else {
            return $this->sendError(['Unauthenticated'], 'You aren\'t signed in before', 401);
        }
    }


    public function AdminLogin(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,

        ];
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
            'password' => ['required', 'min:8'],

        ]);

        if (Auth::attempt($data)) {
            $user = Auth::user();

            $success['token'] = $user->createToken('ProgrammingLanguageProject')->accessToken;
            $success['user_name'] = $user->user_name;
            return $this->sendResponse($success, 'login done successfully');
        } else {
            if ($validator->fails()) {
                return $this->sendError('Unauthorized', $validator->errors());
            }
            return $this->sendError('Unauthorized', ['user number or password isn\'t correct']);
        }
    }
}
