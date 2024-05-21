<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\HallCapacity;
use App\Models\View;
use App\Models\LocationCoordinates;
use App\Models\Rating;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HallController extends Controller
{
    public function index()
    {
        $halls = Hall::all();

        if($halls->count()<1){
            return response([
                'message'=>'There are no halls yet.'
            ]);
        }

        return Hall::
        with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
        ->get();

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
            'langitude'=>'required|numeric'
        ]);

        $locationCoordinates = LocationCoordinates::
        where('latitude',$request->latitude)
        ->where('langitude',$request->langitude)
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
        ->where('location_coordinates_id',$locationCoordinates_id);
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
            $halls[] = Hall::
            where('rating_id','=',$rating->id)
            ->with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')
            ->get();
        }
        return $halls;

    }

    public function lowestPrice()
    {
        $halls = Hall::
        query()->orderBy('price_per_hour')
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
        $halls = Hall::
        query()->orderBy('price_per_hour','desc')
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

    public function lowestSpace()
    {
        $halls = Hall::
        query()->orderBy('space')
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

    public function highestSpace()
    {
        $halls = Hall::
        query()->orderBy('space','desc')
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
        if($hall->count()<1)
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

    public function update(Request $request,$id)
    {
        $request->validate([
            'location_name'=>'string|required_with:location_description,location_latitude,location_langitude',
            'location_description'=>'string|required_with:location_name,location_latitude,location_langitude',
            'location_latitude'=>'numeric|required_with:location_name,location_description,location_langitude',
            'location_langitude'=>'numeric|required_with:location_name,location_description,location_latitude',

            'open_time'=>'date_format:H:i:s|required_with:close_time',
            'close_time'=>'date_format:H:i:s|required_with:open_time',

            'hall_capacity_maximum'=>'integer|required_with:hall_capacity_minimum',
            'hall_capacity_minimum'=>'integer|required_with:hall_capacity_maximum',

            'hall_type_id'=>'integer|min:1|max:3|required_without_all:location_name,location_description,location_latitude,location_langitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,name,space,price_per_hour,license_image,panorama_image,external_image',
            'name'=>'string|required_without_all:location_name,location_description,location_latitude,location_langitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,space,price_per_hour,license_image,panorama_image,external_image',
            'space'=>'numeric|required_without_all:location_name,location_description,location_latitude,location_langitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,price_per_hour,license_image,panorama_image,external_image',
            'price_per_hour'=>'numeric|required_without_all:location_name,location_description,location_latitude,location_langitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,space,license_image,panorama_image,external_image',
            'license_image'=>'image|required_without_all:location_name,location_description,location_latitude,location_langitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,space,price_per_hour,panorama_image,external_image',
            'panorama_image'=>'image|required_without_all:location_name,location_description,location_latitude,location_langitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,space,price_per_hour,license_image,external_image',
            'external_image'=>'image|required_without_all:location_name,location_description,location_latitude,location_langitude,open_time,close_time,hall_capacity_maximum,hall_capacity_minimum,hall_type_id,name,space,price_per_hour,license_image,panorama_image',
        ]);

        $hall = Hall::find($id);
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
            $locationCoordinates->langitude = $request->get('location_langitude');
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
            $path = 'update/hall/license/';
            $file->move($path,$fileName);
            $hall->license_image = $path.$fileName;
            $hall->save();
        }

        if($request->has('panorama_image'))
        {
            $file= $request->file('panorama_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.panorama.'.$extension;
            $path = 'update/hall/panorama/';
            $file->move($path,$fileName);
            $hall->panorama_image = $path.$fileName;
            $hall->save();
        }

        if($request->has('external_image'))
        {
            $file = $request->file('external_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.external.'.$extension;
            $path = 'update/hall/external/';
            $file->move($path,$fileName);
            $hall->external_image = $path.$fileName;
            $hall->save();
        }
        
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
        where('name','like','%'.$location.'%')->get();
        $hallCapacity = HallCapacity::
        whereBetween('recommended',[$min_capacity,$max_capacity])->get();

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
