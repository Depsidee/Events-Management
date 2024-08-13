<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\HomeReservation;
use App\Models\Reservation;
use App\Models\ReservationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;

class ReservationTypeController extends BaseController
{
    public function index()
    {
        $type = ReservationType::get();
        if($type->count()<1)
        {
            return response([
                'message'=>'There are no types yet.'
            ]);
        }

        return $type;
    }

    public function reservationsOfType($id)
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $type = ReservationType::where('id','=',$id)->first();
        if($type==null)
        {
            return response([
                'message'=>'There is no type with such id.'
            ]);
        }

        $reservations = Reservation::
            with('user','hall','decoration','payment','photography','foodRequest','songRequest')
            ->where('reservation_type_id','=',$id)
            ->where('has_recorded','=','1')
            ->get();

        if($reservations->count()<1)
        {
            return response([
                'message'=>'There are no reservations with this type yet.'
            ]);
        }

        return $reservations;

    }

    public function homeReservationsOfType($id)
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $type = ReservationType::where('id','=',$id)->first();
        if($type==null)
        {
            return response([
                'message'=>'There is no type with such id.'
            ]);
        }

        $homeReservations = HomeReservation::
            with('user','decoration','payment','photography','foodHome')
            ->where('reservation_type_id','=',$id)
            ->where('has_recorded','=','1')
            ->get();

        if($homeReservations->count()<1)
        {
            return response([
                'message'=>'There are no home reservations with this type yet.'
            ]);
        }

        return $homeReservations;

    }

    public function hallReservationsOfType($id)
    {
        if(!(Auth::user()->role_name=='admin_hall'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $type = ReservationType::where('id','=',$id)->first();
        if($type==null)
        {
            return response([
                'message'=>'There is no type with such id.'
            ]);
        }
        $userId = auth()->user()->id;
        $hall= Hall::where('user_id','=',$userId)->first();
        $reservations = Reservation::
            with('user','hall','decoration','payment','photography','foodRequest','songRequest')
            ->where('reservation_type_id','=',$id)
            ->where('hall_id','=',$hall->id)
            ->where('has_recorded','=','1')
            ->get();

        if($reservations->count()<1)
        {
            return response([
                'message'=>'There are no reservations with this type yet.'
            ]);
        }

        return $reservations;

    }

    public function userReservationsOfType($id)
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $type = ReservationType::where('id','=',$id)->first();
        if($type==null)
        {
            return response([
                'message'=>'There is no type with such id.'
            ]);
        }

        $userId = auth()->user()->id;
        $reservations = Reservation::
            with('user','hall','decoration','payment','photography','foodRequest','songRequest')
            ->where('reservation_type_id','=',$id)
            ->where('user_id','=',$userId)
            ->where('has_recorded','=','1')
            ->get();

        if($reservations->count()<1)
        {
            return response([
                'message'=>'There are no reservations with this type yet.'
            ]);
        }

        return $reservations;

    }

    public function userHomeReservationsOfType($id)
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $type = ReservationType::where('id','=',$id)->first();
        if($type==null)
        {
            return response([
                'message'=>'There is no type with such id.'
            ]);
        }

        $userId = auth()->user()->id;
        $homeReservations = HomeReservation::
            with('user','decoration','payment','photography','foodHome')
            ->where('reservation_type_id','=',$id)
            ->where('user_id','=',$userId)
            ->where('has_recorded','=','1')
            ->get();

        if($homeReservations->count()<1)
        {
            return response([
                'message'=>'There are no home reservations with this type yet.'
            ]);
        }

        return $homeReservations;

    }
}
