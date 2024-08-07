<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource ;
use App\Rules\GmailValidation;

class userController extends BaseController
{


////show all users :
    public function index()
    {

        $users = User::where('role')->get();
        // dd($users);
        if(Auth::user()->role_name=='super_admin')
       { return $this->sendResponse([$users ] , 'Our pharmacies data retrived successfully');}
       else{
        return $this->sendError('you don\'t have permission' ,'' ,403);
       }
    }

/////////
/////////show personal informition
/////////
    public function showPersonalInfo($id)
    {
        $user = User::find($id);
        // dd($user , Auth::user());
        // dd(Auth::user()->id);
        if(Auth::user()->id != $user->id){
            return $this->sendError('don\'t have permission to fetch this data' ,'' ,403);
        }
        return $this->sendResponse($user ,'user data retrivied successfully');
    }



    ////////
    ///////update_user
   ///////
    public function updatePesonalInfo(Request $request,  $id)
    {
        $user = User::find($id);
        if(Auth::user()->id != $user->id || Auth::user()->role_name=='super_admin'){
            return $this->sendError('don\'t have permission to fetch this data' ,'' ,403);
        }
        else{
            $validator = Validator::make($request->all(),[
                'user_name' => ['required', 'unique:users', 'max:10', 'min:4', 'string', 'regex:/^[a-zA-Z]+$/'],
                'phone_number' => ['required', 'digits:10'],
                'email' => ['required', new GmailValidation],
                'password' => ['required', 'min:9', 'max:15'],
                'profile_image' => ['image'],
        ]);
        if($validator->fails()){
            return $this->sendError('validate your data,' , $validator->errors());
        }
        if($request->has('profile_image'))
        {
            $file= $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.profile_image.'.$extension;
            $path = 'users/update/';
            $file->move($path,$fileName);

        }
        // if ($request->has('profile_image')) {
        //     $profile_image = $request->profile_image;
        //     $newPhoto = time().$profile_image->getClientOriginalName();
        //     $profile_image->move('update/users',$newPhoto);
        //     $user->profile_image ='update/users/'.$newPhoto ;
        // }


        $user->user_name = $request->user_name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->profile_image = $fileName;
     $user->save();

        return $this->sendResponse($user ,  'updated data done successfully');}

    }
///////
///update_admin
////////
    public function updatePesonalInfo_Admin(Request $request,  $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'min:8'],
        ]);
         if(Auth::user()->role_name=='super_admin'){
        if($validator->fails()){
            return $this->sendError('validate your data,' , $validator->errors());
        }
        $user->email = $request->email;
        $user->password = $request->password;

            $user->save();
            return $this->sendResponse(new UserResource($user) ,  'updated data done successfully');
        }
        else{
         return $this->sendError('you don\'t have permission' ,'' ,403);
        }

    }
}

