<?php

namespace App\Http\Controllers;

use App\Models\HomeReservation;
use Illuminate\Support\Facades\Auth ;
use App\Models\Photography;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PhotographyController extends BaseController
{
    public function indexPhotography()
    {
        if(Auth::user()->role_name=='admin_hall')
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $photogrphies = Photography::get();
        if($photogrphies->count()<1)
        {
            return response([
                'message'=>'There are no photographies yet.'
            ]);
        }

        return $photogrphies;
    }

    public function addPhotography(Request $request)
    {
        $request->validate([
            'photographer_name'=>'required|string',
            'price'=>'required|numeric'
        ]);

        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $photogrpher_name = $request->get('photographer_name');
        $price = $request->get('price');
        $photogrphy = Photography::create([
            'photographer_name'=>$photogrpher_name,
            'price'=>$price
        ]);

        return response([
            'message'=>'The photography has been created successfully',
            'photography'=>$photogrphy
        ]);
    }

    public function updatePhotography(Request $request)
    {
        $request->validate([
            'photography_id'=>'required|integer',
            'price'=>'required|numeric'
        ]);

        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $photogrphy_id = $request->get('photography_id');
        $photogrphy = Photography::where('id','=',$photogrphy_id)->first();
        if($photogrphy==null)
        {
            return response([
                'message'=>'There is no photography with such id.'
            ]);
        }

        $photogrphy['price'] = $request->get('price');
        $photogrphy->save();
        return response([
            'message'=>'The photography has been updated successfully.',
            'photography'=>$photogrphy
        ]);
    }

    public function deletePhotography($id)
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $photogrphy = Photography::where('id','=',$id)->first();
        if($photogrphy==null)
        {
            return response([
                'message'=>'There is no photography with such id.'
            ]);
        }

        Photography::where('id','=',$id)->delete();
        return response([
            'message'=>'The photography has been deleted successfully.'
        ]);
    }

    public function availablePhotography($date)
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $date = Carbon::parse($date);
        $reservedPhotographyIds = Reservation::where('date','=', $date)
            ->pluck('photography_id');
        $homeReservedPhotographyIds = HomeReservation::where('date','=',$date)
            ->pluck('photography_id');
        $allReservedPhotographyIds = $reservedPhotographyIds->merge($homeReservedPhotographyIds);

        $photogrphy = Photography::whereNotIn('id', $allReservedPhotographyIds)->get();
        if($photogrphy->count()<1)
        {
            return response([
                'message'=>'There are no available photographies in this date.'
            ]);
        }

        return $photogrphy;
    }
}
