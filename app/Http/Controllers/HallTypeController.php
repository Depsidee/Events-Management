<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth ;
use App\Models\HallType;
use App\Models\Hall;
use Illuminate\Http\Request;

class HallTypeController extends Controller
{
    public function index()
    {
        if(Auth::user()->role_name=='admin_hall')
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }
        $type = HallType::all();
        if($type->count()<1)
        {
            return response([
                'message'=>'There is no types yet.'
            ]);
        }

        return $type;
    }

    public function hallsOfType($id)
    {
        if(Auth::user()->role_name=='admin_hall')
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $type = HallType::where('id','=',$id)->first();
        if($type==null)
        {
            return response([
                'message'=>'There is no type with such id.'
            ]);
        }

        $halls = Hall::
            with('user','locationCoordinates','workTime','hallCapacity','rating')
            ->where('hall_type_id','=',$id)
            ->where('has_recorded','=','1')
            ->get();

        if($halls->count()<1)
        {
            return response([
                'message'=>'There is no halls with this type.'
            ]);
        }

        return $halls;

    }
}
