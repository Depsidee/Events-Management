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
   public function updatePesonalInfo(Request $request, $id)
   {
       $user = User::find($id);

       // Validation rules array
       $rules = [];

       if ($request->has('user_name') && $request->user_name != $user->user_name) {
           $rules['user_name'] = ['unique:users', 'max:10', 'min:4', 'string', 'regex:/^[a-zA-Z]+$/'];
       }

       if ($request->has('phone_number') && $request->phone_number != $user->phone_number) {
           $rules['phone_number'] = ['digits:10'];
       }

       if ($request->has('email') && $request->email != $user->email) {
           $rules['email'] = [new GmailValidation];
       }

       if ($request->has('password')) {
           $rules['password'] = ['min:8', 'max:15'];
       }

       if ($request->has('profile_image')) {
           $rules['profile_image'] = ['image'];
       }

       // Validate request
       $validator = Validator::make($request->all(), $rules);

       if ($validator->fails()) {
           return $this->sendError('Validate your data', $validator->errors());
       }

       // Update the profile image if provided
       $fileName = null;
       $path = null;
       if ($request->has('profile_image')) {
           $file = $request->file('profile_image');
           $extension = $file->getClientOriginalExtension();
           $fileName = time() . '.profile_image.' . $extension;
           $path = 'users/update/';
           $file->move($path, $fileName);
           $user->profile_image = $path . $fileName;
       }

       // Update the user info only if the input is provided and different
       if ($request->has('user_name') && $request->user_name != $user->user_name) {
           $user->user_name = $request->user_name;
       }

       if ($request->has('phone_number') && $request->phone_number != $user->phone_number) {
           $user->phone_number = $request->phone_number;
       }

       if ($request->has('email') && $request->email != $user->email) {
           $user->email = $request->email;
       }

       if ($request->has('password')) {
           $user->password = $request->password;
       }

       $user->save();

       return $this->sendResponse($user, 'Updated data successfully');
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


        if ($request->has('user_name') && $request->user_name != $user->user_name) {
            $user->user_name = $request->user_name;
        }

        if ($request->has('phone_number') && $request->phone_number != $user->phone_number) {
            $user->phone_number = $request->phone_number;
        }

        if ($request->has('email') && $request->email != $user->email) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = $request->password;
        }

            $user->save();
            return $this->sendResponse(new UserResource($user) ,  'updated data done successfully');
        }
        else{
         return $this->sendError('you don\'t have permission' ,'' ,403);
        }

    }
}

