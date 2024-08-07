<?php

namespace App\Http\Controllers;

use App\Http\Resources\hallResource as hallResource;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth ;
use App\Models\Hall;
use App\Models\User;
use App\Models\HallCapacity;
use App\Models\View;
use App\Models\LocationCoordinates;
use App\Models\Rating;
use App\Models\WorkTime;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HallController extends BaseController

{
    //////halls with favorite
    public function index_withfavorite(){
        if(Auth::user()->role_name=='client'){
           $halls = Hall::where('has_recorded','=','1')
        ->get();


        //$medicines = Medicine::paginate(); // show every 15 item
        if(!Isset($halls) ){
            return $this->sendError('There is no medicine yet');
        }
        else{
        }
  //      $favorites = Favorite::all();

            return $this->sendResponse(hallResource::collection($halls) , 'all medicines retrived successfully');}
else {
    return $this->sendError('you don\'t have permission' ,403);
}
    }



    //////////
    public function index()
    {
        if(Auth::user()->role_name=='admin_hall')
        {
            return $this->sendError('you don\'t have permission' ,403);
        }

        $halls = Hall::
                where('has_recorded','=','1')
                ->with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
                ->get();

        if($halls->count()<1){
            return response([
                'message'=>'There are no halls yet.'
            ]);
        }

        return $halls;

    }

    public function unrecordedHalls()
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $halls = Hall::
                where('has_recorded','=','0')
                ->with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
                ->get();

        if($halls->count()<1)
        {
            return response([
                'message'=>'There are no unrecorded halls'
            ]);
        }

        return $halls;
    }

    public function acceptHall($id)
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $hall = Hall::find($id);
        if($hall==null)
        {
            return response([
                'message'=>'There is no hall with such id'
            ],404);
        }
        if($hall['has_recorded'])
        {
            return response([
                'message'=>'This hall is already accepted'
            ]);
        }
        $hall['Has_recorded']=1;
        $hall->save();
        return response([
            'message'=>'This hall has been accepted successfully'
        ]);
    }

    public function rejectHall($id)
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }


        $hall = Hall::where('id','=',$id)->first();
        if($hall==null)
        {
            return response([
                'message'=>'There is no hall with such id'
            ],404);
        }
        if($hall['has_recorded'])
        {
            return response([
                'message'=>'This hall is already accepted'
            ]);
        }
        User::where('id','=',$hall['user_id'])->delete();
        LocationCoordinates::where('id','=',$hall['location_coordinates_id'])->delete();
        WorkTime::where('id','=',$hall['work_time_id'])->delete();
        HallCapacity::where('id','=',$hall['hall_capacity_id'])->delete();
        Rating::where('id','=',$hall['rating_id'])->delete();
        Hall::where('id','=',$id)->delete();
        return response([
            'message'=>'This hall and its owner have been deleted successfully'
        ]);
    }

    public function hallDetails($id)
    {
        $hall = Hall::
            with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->get()
            ->where('id',$id);
        if($hall->count()<1)
        {
            return response([
                'message'=>'There is no hall with such id.'
            ],404);
        }

        return $hall;

    }

    public function hallFromCoordinates(Request $request)
    {
        $request->validate([
            'latitude'=>'required|numeric',
            'longitude'=>'required|numeric'
        ]);

        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $locationCoordinates = LocationCoordinates::
            where('latitude',$request->latitude)
            ->where('longitude',$request->longitude)
            ->first();
        if($locationCoordinates==null)
        {
            return response([
                'message'=>'There is no hall with such coordinates.'
            ],404);
        }

        $locationCoordinates_id = $locationCoordinates->id;
        $hall = Hall::
            with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->get()
            ->where('location_coordinates_id',$locationCoordinates_id)
            ->where('has_recorded','=','1');
        if($hall==null)
        {
            return response([
                'message'=>'There is no hall with such coordinates.'
            ],404);
        }

        return $hall;
    }

    public function showAccordingRating()
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $ratings = Rating::query()->orderBy('points','desc')->get();
        if($ratings->count()<1)
        {
            return response([
                'message'=>'There are no halls yet.'
            ]);
        }

        $halls = array();
        foreach($ratings as $rating)
        {
            $hall = Hall::
            where('rating_id','=',$rating->id)
            ->where('has_recorded','=','1')
            ->with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->first();
            if($hall!=null)
            {
            $halls[] = $hall;
            }
        }
        return $halls;

    }

    public function lowestPrice()
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $halls = Hall::
            query()->orderBy('price_per_hour')
            ->where('has_recorded','=','1')
            ->with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->get();
        if($halls->count()<1)
        {
            return response([
                'mossage'=>'There are no halls yet.'
            ]);
        }

        return $halls;
    }

    public function highestPrice()
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $halls = Hall::
            query()->orderBy('price_per_hour','desc')
            ->where('has_recorded','=','1')
            ->with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->get();
        if($halls->count()<1)
        {
            return response([
                'mossage'=>'There are no halls yet.'
            ]);
        }

        return $halls;
    }

    public function smallestSpace()
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $halls = Hall::
            query()->orderBy('space')
            ->where('has_recorded','=','1')
            ->with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->get();
        if($halls->count()<1)
        {
            return response([
                'mossage'=>'There are no halls yet.'
            ]);
        }

        return $halls;
    }

    public function largestSpace()
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $halls = Hall::
            query()->orderBy('space','desc')
            ->where('has_recorded','=','1')
            ->with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->get();
        if($halls->count()<1)
        {
            return response([
                'mossage'=>'There are no halls yet.'
            ]);
        }

        return $halls;
    }

    public function hallViews($id)
    {
        $hall = Hall::find($id);
        if($hall==null)
        {
            return response([
                'message'=>'There is no hall with such id.'
            ],404);
        }

        $views = View::where('hall_id','=',$id)->with('user')->get();
        if($views->count()<1)
        {
            return response([
                'message'=>'There are no comments yet.'
            ]);
        }

        return $views;
    }

    public function update(Request $request)
    {
        if(!(Auth::user()->role_name=='admin_hall'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $request->validate([
            'location_name'=>'string|required_with:location_description,location_latitude,location_longitude',
            'location_description'=>'string|required_with:location_name,location_latitude,location_longitude',
            'location_latitude'=>'numeric|required_with:location_name,location_description,location_longitude',
            'location_longitude'=>'numeric|required_with:location_name,location_description,location_latitude',

            'open_time'=>'date_format:H:i:s|required_with:close_time',
            'close_time'=>'date_format:H:i:s|required_with:open_time',

            'hall_capacity_maximum'=>'integer|required_with:hall_capacity_minimum',
            'hall_capacity_minimum'=>'integer|required_with:hall_capacity_maximum',

            'hall_type_id'=>'integer|min:1|max:3|required_without_all:location_name,location_description,location_latitude,location_longitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,name,space,price_per_hour,license_image,panorama_image,external_image',
            'name'=>'string|required_without_all:location_name,location_description,location_latitude,location_longitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,space,price_per_hour,license_image,panorama_image,external_image',
            'space'=>'numeric|required_without_all:location_name,location_description,location_latitude,location_longitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,price_per_hour,license_image,panorama_image,external_image',
            'price_per_hour'=>'numeric|required_without_all:location_name,location_description,location_latitude,location_longitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,space,license_image,panorama_image,external_image',
            'license_image'=>'image|required_without_all:location_name,location_description,location_latitude,location_longitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,space,price_per_hour,panorama_image,external_image',
            'panorama_image'=>'image|required_without_all:location_name,location_description,location_latitude,location_longitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,space,price_per_hour,license_image,external_image',
            'external_image'=>'image|required_without_all:location_name,location_description,location_latitude,location_longitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,space,price_per_hour,license_image,panorama_image',
        ]);
        $user_id = auth()->user()->id;
        $hall = Hall::where('user_id','=',$user_id)->first();
        if($hall==null)
        {
            return response([
                'message'=>'There is no hall with such id.'
            ],404);
        }

        if($request->has('location_name'))
        {
            $locationCoordinates = $hall->locationCoordinates;
            $locationCoordinates->name = $request->get('location_name');
            $locationCoordinates->description = $request->get('location_description');
            $locationCoordinates->latitude = $request->get('location_latitude');
            $locationCoordinates->longitude = $request->get('location_longitude');
            $locationCoordinates->save();
        }

        if($request->has('open_time'))
        {
            $workTime = $hall->workTime;
            $workTime->open_time = $request->get('open_time');
            $workTime->close_time = $request->get('close_time');
            $workTime->save();
        }

        if($request->has('hall_capacity_maximum'))
        {
            $hallCapacity = $hall->hallCapacity;
            $maximum = $request->get('hall_capacity_maximum');
            $minimum = $request->get('hall_capacity_minimum');
            $hallCapacity->maximum = $maximum;
            $hallCapacity->minimum = $minimum;
            $hallCapacity->recommended = (($maximum+$minimum)/2);
            $hallCapacity->save();
        }

        if($request->has('hall_type_id'))
        {
            $hall->hall_type_id = $request->get('hall_type_id');
            $hall->save();
        }

        if($request->has('name'))
        {
            $hall->name = $request->get('name');
            $hall->save();
        }

        if($request->has('space'))
        {
            $hall->space = $request->get('space');
            $hall->save();
        }

        if($request->has('price_per_hour'))
        {
            $hall->price_per_hour = $request->get('price_per_hour');
            $hall->save();
        }

        if($request->has('license_image'))
        {
            $file= $request->file('license_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.license.'.$extension;
            $path = 'halls/update/';
            $file->move($path,$fileName);
            $hall->license_image = $path.$fileName;
            $hall->save();
        }

        if($request->has('panorama_image'))
        {
            $file= $request->file('panorama_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.panorama.'.$extension;
            $path = 'halls/update/';
            $file->move($path,$fileName);
            $hall->panorama_image = $path.$fileName;
            $hall->save();
        }

        if($request->has('external_image'))
        {
            $file = $request->file('external_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.external.'.$extension;
            $path = 'halls/update/';
            $file->move($path,$fileName);
            $hall->external_image = $path.$fileName;
            $hall->save();
        }

        $id = $hall->id;
        $hall = Hall::
            with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->get()
            ->where('id','=',$id)
            ->first();
        return response([
            'message'=>'The hall has been updated successfully.',
            'hall'=>$hall
        ]);
    }

    public function hallsAccordingQuestions(Request $request)
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $request->validate([
            'location_name'=>'required|string',
            'hall_capacity'=>'required|integer',
            'hall_type_id'=>'required|integer|min:1|max:3',
            'price_per_hour'=>'required|numeric'
        ]);

        $location = $request->get('location_name');
        $capacity = $request->get('hall_capacity');
        $typeId = $request->get('hall_type_id');
        $price = $request->get('price_per_hour');

        $min_capacity = $capacity-100;
        $max_capacity = $capacity+100;
        $min_price = $price-100000;
        $max_price = $price+100000;

        $locationCoordinates = LocationCoordinates::
            where('name','like','%'.$location.'%')
            ->get();
        $hallCapacity = HallCapacity::
            whereBetween('recommended',[$min_capacity,$max_capacity])
            ->get();

        if($locationCoordinates->count()<1||$hallCapacity->count()<1)
        {
            return response([
                'message'=>'There are no halls with such specifications.'
            ],404);
        }

        $halls = Hall::
            with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->whereBelongsTo($locationCoordinates)
            ->whereBelongsTo($hallCapacity)
            ->whereBetween('price_per_hour',[$min_price,$max_price])
            ->where('hall_type_id',$typeId)
            ->where('has_recorded','=','1')
            ->get();

        if($halls->count()<1)
        {
            return response([
                'message'=>'There are no halls with such specifications.'
            ],404);
        }

        return $halls;
    }

}
