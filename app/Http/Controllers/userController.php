<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource ;

class userController extends BaseController
{



    public function index()
    {

        $users = User::all();
        // dd($users);
        if(Auth::user()->role_name=='admin')
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
        if(Auth::user()->id != $user->id || Auth::user()->role_name=='admin'){
            return $this->sendError('don\'t have permission to fetch this data' ,'' ,403);
        }
        else{
            $validator = Validator::make($request->all(),[
            'user_name' => ['required', 'string', 'max:255' ],
            'phone_number' => ['required' ,'digits:10' ],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'min:8'],
            'profile_image' =>  ['image'],
        ]);
        if($validator->fails()){
            return $this->sendError('validate your data,' , $validator->errors());
        }

        if ($request->has('photo')) {
            $photo = $request->photo;
            $newPhoto = time().$photo->getClientOriginalName();
            $photo->move('uploads/users',$newPhoto);
            $user->photo ='uploads/users/'.$newPhoto ;
        }


        $user->user_name = $request->user_name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->profile_image = $request->profile_image;

        $user->save();
        return $this->sendResponse(new UserResource($user) ,  'updated data done successfully');}

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
        if($validator->fails()){
            return $this->sendError('validate your data,' , $validator->errors());
        }
        $user->email = $request->email;
        $user->password = $request->password;
        if(Auth::user()->role_name=='admin'){
            $user->save();
            return $this->sendResponse(new UserResource($user) ,  'updated data done successfully');
        }
        else{
         return $this->sendError('you don\'t have permission' ,'' ,403);
        }

    }
}

