<?php

namespace App\Http\Controllers;

use App\Models\Decoration;
use App\Models\Food;
use App\Models\FoodHome;
use App\Models\HomeReservation;
use App\Models\LocationCoordinates;
use App\Models\Payment;
use App\Models\Photography;
use App\Models\Reservation;
use App\Models\ReservationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;

class HomeReservationController extends BaseController
{
    public function index()
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $reservations = HomeReservation::
        with('user','reservationType','locationCoordinates','decoration','payment','photography','foodHome')
        ->where('has_recorded','=','1')
        ->get();
        if($reservations->count()<1)
        {
            return response([
                'message'=>'There are no home reservations yet'
            ]);
        }

        return $reservations;

    }

    public function pendingHomeReservations()
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $homeReservations = HomeReservation::
        with('user','reservationType','locationCoordinates','decoration','payment','photography','foodHome')
        ->where('has_recorded','=','0')
        ->get();
        if($homeReservations->count()<1)
        {
            return response([
                'message'=>'There are no home reservations yet'
            ]);
        }

        return $homeReservations;
    }

    public function userHomeReservations()
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }
       $userId = auth()->user()->id;
        $reservations = HomeReservation::
        with('user','reservationType','locationCoordinates','decoration','payment','photography','foodHome')
        ->where('user_id','=',$userId)
        ->where('has_recorded','=','1')
        ->get();
        if($reservations->count()<1)
        {
            return response([
                'message'=>'There are no home reservations yet'
            ]);
        }

        return $reservations;

    }

    public function addHomeReservation(Request $request)
    {
        $request->validate([
            'reservation_type_id'=>'required|integer',
            'decoration_id'=>'required|integer',
            'photography_id'=>'required|integer',
            'date'=>'required|date_format:Y/m/d',
            'start_time'=>'required|date_format:H:i:s',
            'period'=>'required|integer',
            'foodRequest'=>'required|array|min:1',

            'location_latitude'=>'required|numeric',
            'location_longitude'=>'required|numeric',
            'location_name'=>'required|string',
            'location_description'=>'required|string',
        ]);

        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $now = date("Y/m/d");
        $date = $request->get('date');
        if($date<=$now)
        {
            return response([
                'message'=>"You Can't add a reservation with a past date."
            ]);
        }

        $reservation_type_id = $request->get('reservation_type_id');
        $reservation_type = ReservationType::find($reservation_type_id)->first();
        if($reservation_type==null)
        {
            return response([
                'message'=>'Threre is no reservation type with such id.'
            ],404);
        }
        $date = $request->get('date');
        $decoration_id = $request->get('decoration_id');
        $decoration = Decoration::find($decoration_id)->first();
        if($decoration==null)
        {
            return response([
                'message'=>'Threre is no decoration with such id.'
            ],404);
        }
        $photography_id = $request->get('photography_id');
        $photography = Photography::find($photography_id)->first();
        if($photography==null)
        {
            return response([
                'message'=>'Threre is no photography with such id.'
            ],404);
        }
        $photographyReservation = Reservation::
            where('photography_id','=',$photography_id)
            ->where('date','=',$date)
            ->first();
        if($photographyReservation!=null)
        {
            return response([
                'message'=>"This photographer has a reservation in the same date and can't  be reserved."
            ]);
        }
        $photographyHomeReservation = HomeReservation::
            where('photography_id','=',$photography_id)
            ->where('date','=',$date)
            ->first();
        if($photographyHomeReservation!=null)
        {
            return response([
                'message'=>"This photographer has a reservation in the same date and can't  be reserved."
            ]);
        }

        $user_id=auth()->user()->id;
        $totalPrice = 0;
        $totalPrice += $photography['price'];
        $totalPrice += $decoration['price'];
        $payment = Payment::create([
            'status'=>'unpaid',
            'amount'=>$totalPrice
        ]);
        $location = LocationCoordinates::create([
            'name'=>$request->get('location_name'),
            'description'=>$request->get('location_description'),
            'latitude'=>$request->get('location_latitude'),
            'longitude'=>$request->get('location_longitude')
        ]);
        $homeReservation = HomeReservation::create([
            'user_id'=>$user_id,
            'reservation_type_id'=>$request->get('reservation_type_id'),
            'location_coordinates_id'=>$location->id,
            'decoration_id'=>$decoration_id,
            'payment_id'=>$payment->id,
            'photography_id'=>$photography_id,
            'has_recorded'=>false,
            'date'=>$request->get('date'),
            'start_time'=>$request->get('start_time'),
            'period'=>$request->get('period'),
            'total_price'=>$totalPrice,
        ]);
        foreach($request['foodRequest'] as $foodRequest)
        {
            $food = Food::where('id',$foodRequest['id'])->first();
            if($food==null)
            {
                HomeReservation::where('id','=',$homeReservation->id)->delete();
                return response([
                    'message'=>'There is no food with id:'.$foodRequest['id']
                ],404);
            }
            $requestFood = FoodHome::create([
                'food_id'=>$foodRequest['id'],
                'home_reservation_id'=>$homeReservation->id,
                'amount'=>$foodRequest['amount'],
                'total_price'=>$food['price']*$foodRequest['amount']
            ]);
            $totalPrice+=$requestFood['total_price'];
        }
        $homeReservation->total_price = $totalPrice;
        $homeReservation->save();
        $payment->amount = $totalPrice;
        $payment->save();

        $myReservation = HomeReservation::
            with('user','reservationType','locationCoordinates','decoration','payment','photography','foodHome')
            ->where('id','=',$homeReservation->id)
            ->first();
        return Response([
            'message'=>'The reservation has been added successfully and waiting for the admin to approve it.',
            'reservation'=>$myReservation
        ]);
    }

    public function updateHomeReservation(Request $request)
    {
        $request->validate([
            'home_reservation_id'=>'required|integer',
            'hours'=>'required|integer'
        ]);

        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $homeReservation = HomeReservation::where('id','=',$request['home_reservation_id'])->first();
        if($homeReservation==null)
        {
            return response([
                'message'=>'There id no home reservation with such id'
            ],404);
        }
        $user_id = auth()->user()->id;
        if($homeReservation['user_id'] != $user_id)
        {
            return response([
                'message'=>"You don't have permission to update this reservation"
            ]);
        }

        $period = $homeReservation['period'];
        $homeReservation['period'] = $period+$request['hours'];
        $homeReservation->save();
        $myReservation = HomeReservation::
                        with('user','reservationType','locationCoordinates','decoration','payment','photography','foodHome')
                        ->where('id','=',$request['home_reservation_id'])
                        ->first();
                    
        return response([
            'message'=>'The reservation has been updated successfully',
            'home reservation'=>$myReservation
        ]);
    }

    public function deleteHomeReservation($id)
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $homeReservation = HomeReservation::where('id','=',$id)->first();
        if($homeReservation==null)
        {
            return response([
                'message'=>'There id no home reservation with such id'
            ]);
        }
        $user_id = auth()->user()->id;
        if($homeReservation['user_id'] != $user_id)
        {
            return response([
                'message'=>"You don't have permission to delete this reservation"
            ]);
        }
        $payment = Payment::where('id','=',$homeReservation['payment_id'])->first();
        if($payment['status']=='paid')
        {
            return response([
                'message'=>"This reservation is paid and can't be deleted"
            ]);
        }

        FoodHome::where('home_reservation_id','=',$id)->delete();
        Payment::where('id','=',$homeReservation['payment_id'])->delete();
        HomeReservation::where('id','=',$id)->delete();

        return response([
            'message'=>'The reservation has been deleted successfully'
        ]);
    }

    public function allPreviousHomeReservations()
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $now = date("Y-m-d");
        $homeReservations = HomeReservation::
        query()->orderBy('date','desc')
        ->with('user','reservationType','locationCoordinates','decoration','payment','photography','foodHome')
        ->where('has_recorded','=','1')
        ->where('date','<',$now)
        ->get();
        if($homeReservations->count()<1)
        {
            return response([
                'message'=>'There are no home reservations yet'
            ]);
        }

        return $homeReservations;
    }

    public function allUpcomingHomeReservations()
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $now = date("Y-m-d");
        $homeReservations = HomeReservation::
        query()->orderBy('date')
        ->with('user','reservationType','locationCoordinates','decoration','payment','photography','foodHome')
        ->where('has_recorded','=','1')
        ->where('date','>=',$now)
        ->get();
        if($homeReservations->count()<1)
        {
            return response([
                'message'=>'There are no home reservations yet'
            ]);
        }

        return $homeReservations;
    }

    public function userPreviousHomeReservations()
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $userId = auth()->user()->id;
        $now = date("Y-m-d");
        $homeReservations = HomeReservation::
        query()->orderBy('date','desc')
        ->with('user','reservationType','locationCoordinates','decoration','payment','photography','foodHome')
        ->where('user_id','=',$userId)
        ->where('has_recorded','=','1')
        ->where('date','<',$now)
        ->get();
        if($homeReservations->count()<1)
        {
            return response([
                'message'=>'There are no home reservations yet'
            ]);
        }

        return $homeReservations;
    }

    public function userUpcomingHomeReservations()
    {
        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $userId = auth()->user()->id;
        $now = date("Y-m-d");
        $homeReservations = HomeReservation::
        query()->orderBy('date')
        ->with('user','reservationType','locationCoordinates','decoration','payment','photography','foodHome')
        ->where('user_id','=',$userId)
        ->where('has_recorded','=','1')
        ->where('date','>=',$now)
        ->get();
        if($homeReservations->count()<1)
        {
            return response([
                'message'=>'There are no home reservations yet'
            ]);
        }

        return $homeReservations;
    }


}
