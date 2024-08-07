<?php

namespace App\Http\Controllers;
use Throwable;
use App\Services\UserService;
use App\Http\Requests\clientRequest;
use App\Http\Requests\AdminHallRequeat;
use App\Http\Responces\Responses;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LocationCoordinates;
use App\Models\Hall;
use App\Models\HallCapacity;
use App\Models\WorkTime;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Hash;
use App\Rules\GmailValidation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class AuthController extends BaseController

{
    private UserService $UserService;
    public function __construct(UserService $UserService)
    {
$this->UserService=$UserService;
    }

    public function Register(Request $request)
    {
        {
            $input = $request->all();
            $validator = Validator::make($input, [
                'user_name' => ['required', 'unique:users', 'max:10', 'min:4', 'string', 'regex:/^[a-zA-Z]+$/'],
                'phone_number' => ['required', 'unique:users', 'digits:10'],
                'email' => ['required', 'unique:users', new GmailValidation],
                'password' => ['required', 'min:9', 'max:15'],
                'profile_image' =>  'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return ['Validate your data', $validator->errors()];
            }

            $role_name = 'client';

            $fileName = null;

            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'.profile_image.'.$extension;
                $path = 'users/storagee/';
                $file->move($path, $fileName);
            }

            $user = User::query()->create([
                'user_name' => $request->user_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'role_name' => $role_name,
                'profile_image' => $fileName,
            ]);
            $success['token'] = $user->createToken('ProgrammingLanguageProject')->accessToken;
            $success['user_name'] = $user->user_name;
            $success['phone_number'] = $user->phone_number;
            $success['email'] = $user->email;
            $success['profile_image'] = $user->profile_image;
            $success['role_name'] = $user->role_name;

            $message = 'registration done successfully';
            return [$success, $message];
        }
    }

    ////////////////////////
    ////////register for admin hall
    /////////////////////
    public function Register_adminHall(Request $request)
    {

            $input = $request->all();
            $validator = Validator::make($input, [
                //user_admin_hall:
                'user_name' => ['required', 'unique:users', 'max:10', 'min:4', 'string', 'regex:/^[a-zA-Z]+$/'],
                'phone_number' => ['required', 'unique:users', 'digits:10'],
                'email' => ['required', 'unique:users', new GmailValidation],
                'password' => ['required', 'min:9', 'max:15'],

                'profile_image' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048',
                //hall:
                'name' => ['required', 'string'],
                'price_per_hour' => 'required',
                'space' => 'required',
                'license_image' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048',
                'external_image' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048',
                'panorama_image' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048',
                //work Time:
                'close_time' => 'required',
                'open_time' => 'required',
                //
                //location:
                //
                'name_region' => ['required', 'string'],
                'description_region' => ['required', 'string'],
                'longitude' => 'required',
                'latitude' => 'required',
                // HallCapacity:
                'minimum' => 'required',
                'maximum' => 'required',

                "hall_type_id" => 'required'

            ]);

            if ($validator->fails()) {
                return ['Validate your data', $validator->errors()];
            }

                ///////
                //create user
                /////

                $file= $request->file('profile_image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'.profile_image.'.$extension;
                $path = 'users/storagee/';
                $file->move($path,$fileName);


                $role_name = 'admin_hall';
                $user = User::query()->create([
                    'user_name' => $request->user_name,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'password' => Hash::make('password'),
                    'role_name' => $role_name,
                    'profile_image' => $fileName
                ]);


                //////////////
                //create work time:
                //////////////

                $Work_time = WorkTime::create([
                    'open_time' => $request->open_time,
                    'close_time' => $request->close_time
                ]);
                ///////
                ////create location
                //////////
                $location_coordinate = LocationCoordinates::create([
                    'name' => $request->name_region,
                    'description' => $request->description_region,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude
                ]);
                /////////
                ///create hall capacity
                /////////
                $recommended = ($request->minimum + $request->maximum) / 2;

                $hall_capacity = HallCapacity::create([
                    'minimum' => $request->minimum,
                    'maximum' => $request->maximum,
                    'recommended' => $recommended,

                ]);
                ///////
                //create hall
                /////////

                    $file= $request->file('license_image');
                    $extension = $file->getClientOriginalExtension();
                    $license_image = time().'.license_image.'.$extension;
                    $path = 'halls/storagee/';
                    $file->move($path,$license_image);



                    $file= $request->file('external_image');
                    $extension = $file->getClientOriginalExtension();
                    $external_image = time().'.external_image.'.$extension;
                    $path = 'halls/storagee/';
                    $file->move($path,$external_image);


                    $file= $request->file('panorama_image');
                    $extension = $file->getClientOriginalExtension();
                    $panorama_image = time().'.panorama_image.'.$extension;
                    $path = 'halls/storagee/';
                    $file->move($path,$panorama_image);


                $hall = Hall::create(
                    [
                        'user_id' => $user->id,
                        'name' => $request->name,
                        'location_coordinates_id' => $location_coordinate->id,
                        'hall_type_id' => $request->hall_type_id,
                        'hall_capacity_id' => $hall_capacity->id,
                        'price_per_hour' => $request->price_per_hour,
                        'space' => $request->space,
                        'work_time_id' => $Work_time->id,
                        'license_image' =>$license_image,
                        'external_image' => $external_image,
                        'panorama_image' => $panorama_image,

                    ]
                );
                $success['token'] = $user->createToken('ProgrammingLanguageProject')->accessToken;


                $message = 'user and your hall is created successfully';
                return [$success, $user, $hall, $hall_capacity, $location_coordinate, $Work_time, $message];


    }


    ///////////
    ///logout
    ////////////////


    public function logout(Request $request)
    {
        $data = [];
        try {
            $data = $this->UserService->logout($request);
            return ($data);
        } catch (Throwable $th) {

            $message = $th->getMessage();
            return $this->sendError($data, $message);
        }
    }


    ///////////////
    ///login
    /////////////




    public function Login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',

        ]);
        $user=User::where('email',$request->email)->first();
        if(!$user){
            return response()->json([
                'message'=>'email is wrong',
            ]);
        }
        if(!Hash::isHashed($user->password, $request->password)){
            return response()->json([
                'message'=>'password is wrong',
            ]);
        }
        $token= $user->createToken('MyAppToken')->accessToken;
        return response()->json([
            'token'=>$token,'user'=>$user
        ]);}
}
