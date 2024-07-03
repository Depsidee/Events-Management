<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth ;
use App\Models\User;
use App\Models\Payment;
use App\Models\Hall;
use App\Models\Decoration;
use App\Models\Photography;
use App\Models\Reservation;
use App\Models\Food;
use App\Models\FoodRequest;
use App\Models\Song;
use App\Models\SongRequest;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        if(!(Auth::user()->role_name=='admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $reservations = Reservation::
        with('user','hall','decoration','payment','photography','foodRequest','songRequest')
        ->get();
        if($reservations->count()<1)
        {
            return response([
                'message'=>'There are no reservations yet'
            ]);
        }

        return $reservations;

    }

    public function userReservations()
    {
        if(!(Auth::user()->role_name=='user'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }
       $userId = auth()->user()->id;
        $reservations = Reservation::
        with('user','hall','decoration','payment','photography','foodRequest','songRequest')
        ->where('user_id','=',$userId)
        ->get();
        if($reservations->count()<1)
        {
            return response([
                'message'=>'There are no reservations yet'
            ]);
        }

        return $reservations;

    }

    public function HallReservations()
    {
        if(!(Auth::user()->role_name=='admin_hall'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }
       $userId = auth()->user()->id;
       $hall = Hall::where('user_id','=',$userId)->first();
       $reservations = Reservation::
        with('user','hall','decoration','payment','photography','foodRequest','songRequest')
        ->where('hall_id','=',$hall['id'])
        ->get();
        if($reservations->count()<1)
        {
            return response([
                'message'=>'There are no reservations yet'
            ]);
        }

        return $reservations; 

    }

    public function addReservation(Request $request)
    {
        $request->validate([
            'reservation_type_id'=>'required|integer',
            'hall_id'=>'required|integer',
            'decoration_id'=>'required|integer',
            'photography_id'=>'required|integer',
            'date'=>'required|date_format:Y/m/d',
            'period'=>'required|integer',
            'start_time'=>'required|date_format:H:i:s',
            'children_permission'=>'required|boolean',
            'transportation'=>'required|boolean',
            'guest_photography'=>'required|boolean',
            'foodRequest'=>'required|array|min:1',
            'songRequest'=>'required|array|min:1'
        ]);

        if(!(Auth::user()->role_name=='user'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $reservation_type_id = $request->get('reservation_type_id');
        $reservation_type = Hall::find($reservation_type_id)->first();
        if($reservation_type==null)
        {
            return response([
                'message'=>'Threre is no reservation type with such id.'
            ],404);
        }
        $hall_id = $request->get('hall_id');
        $hall = Hall::find($hall_id)->first();
        if($hall==null)
        {
            return response([
                'message'=>'Threre is no hall with such id.'
            ],404);
        }
        $date = $request->get('date');
        $hallReservation = Reservation::
            where('hall_id','=',$hall_id)
            ->where('date','=',$date)
            ->first();
        if($hallReservation!=null)
        {
            return response([
                'message'=>"This hall has a reservation in the same date and can't  be reserved."
            ]);
        }
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
        $workTime = $hall->workTime;
        $startTime = $request->get('start_time');
        $period = $request->get('period');
        $endTime = $startTime;
        $endTime = Carbon::parse($endTime)->addHours($period);
        $startTime = Carbon::parse($startTime);
        $openTime = Carbon::parse($workTime['open_time']);
        $closeTime = Carbon::parse($workTime['close_time']);
        if(!($startTime->between($openTime,$closeTime))||!($endTime->between($openTime,$closeTime)))
        {
            return response([
                'message'=>"The reservation's time is out of the hall's work times."
            ]);
        }

        //$user_id = $request['user_id'];
        $user_id=auth()->user()->id;
        $totalPrice = 0;
        $totalPrice += $hall['price_per_hour']*$request['period'];
        $totalPrice += $photography['price'];
        $totalPrice += $decoration['price'];
        $payment = Payment::create([
            'status'=>'unpaid',
            'amount'=>$totalPrice
        ]);
        $reservation = Reservation::create([
            'user_id'=>$user_id,
            'hall_id'=>$hall_id,
            'reservation_type_id'=>$request->get('reservation_type_id'),
            'decoration_id'=>$decoration_id,
            'payment_id'=>$payment->id,
            'photography_id'=>$photography_id,
            'has_recorded'=>false,
            'date'=>$request->get('date'),
            'period'=>$request->get('period'),
            'start_time'=>$request->get('start_time'),
            'total_price'=>$totalPrice,
            'children_permission'=>$request->get('children_permission'),
            'transportation'=>$request->get('transportation'),
            'guest_photography'=>$request->get('guest_photography')
        ]);
        foreach($request['foodRequest'] as $foodRequest)
        {
            $food = Food::where('id',$foodRequest['id'])->first();
            if($food==null)
            {
                Reservation::where('id','=',$reservation->id)->delete();
                return response([
                    'message'=>'There is no food with id:'.$foodRequest['id']
                ],404);
            }
            $requestFood = FoodRequest::create([
                'food_id'=>$foodRequest['id'],
                'reservation_id'=>$reservation->id,
                'amount'=>$foodRequest['amount'],
                'total_price'=>$food['price']*$foodRequest['amount']
            ]);
            $totalPrice+=$requestFood['total_price'];
        }
        $reservation->total_price = $totalPrice;
        $reservation->save();
        $payment->amount = $totalPrice;
        $payment->save();
        foreach($request['songRequest'] as $songId)
        {
            $song = Song::where('id',$songId)->first();
            if($song==null)
            {
                Reservation::where('id','=',$reservation->id)->delete();
                return response([
                    'message'=>'There is no song with id:'.$songId
                ],404);
            }
            $songRequest = SongRequest::create([
                'reservation_id'=>$reservation->id,
                'song_id'=>$songId
            ]);
        }

        $myReservation = Reservation::
            with('user','hall','decoration','payment','photography','foodRequest','songRequest')
            ->where('id','=',$reservation->id)
            ->first();
        return Response([
            'message'=>'The reservation has been added successfully and waiting for the admin to approve it.',
            'reservation'=>$myReservation
        ]);
    }

    public function updateReservation(Request $request)
    {
        $request->validate([
            'reservation_id'=>'required|integer',
            'hours'=>'required|integer'
        ]);

        if(!(Auth::user()->role_name=='user'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }
        
        $reservation = Reservation::where('id','=',$request['reservation_id'])->first();
        if($reservation==null)
        {
            return response([
                'message'=>'There id no reservation with such id'
            ],404);
        }
        $user_id = auth()->user()->id; 
        if($reservation['user_id'] != $user_id)
        {
            return response([
                'message'=>"You don't have permission to update this reservation"
            ]);
        }

        $hall = Hall::where('id','=',$reservation['hall_id'])->first();
        $workTime = $hall->workTime;
        $startTime = $reservation['start_time'];
        $period = $reservation['period'];
        $endTime = $startTime;
        $endTime = Carbon::parse($endTime)->addHours($period);
        $endTime = Carbon::parse($endTime)->addHours($request['hours']);
        $startTime = Carbon::parse($startTime);
        $openTime = Carbon::parse($workTime['open_time']);
        $closeTime = Carbon::parse($workTime['close_time']);
        if(!($endTime->between($openTime,$closeTime)))
        {
            return response([
                'message'=>"The reservation's time is out of the hall's work times."
            ]);
        }

        $reservation['period'] = $period+$request['hours'];
        $reservation['total_price'] += $hall['price_per_hour']*$request['hours'];
        $reservation->save();
        $payment = Payment::where('id','=',$reservation['payment_id'])->first();
        $payment['amount'] = $reservation['total_price'];
        $payment->save();

        return response([
            'message'=>'The reservation has been updated successfully'
        ]);
    }

    public function deleteReservation($id)
    {
        if(!(Auth::user()->role_name=='user'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }
        
        $reservation = Reservation::where('id','=',$id)->first();
        if($reservation==null)
        {
            return response([
                'message'=>'There id no reservation with such id'
            ]);
        }
        $user_id = auth()->user()->id; 
        if($reservation['user_id'] != $user_id)
        {
            return response([
                'message'=>"You don't have permission to delete this reservation"
            ]);
        }
        $payment = Payment::where('id','=',$reservation['payment_id'])->first();
        if($payment['status']=='paid')
        {
            return response([
                'message'=>"This reservation is paid and can't be deleted"
            ]);
        }

        FoodRequest::where('reservation_id','=',$id)->delete();
        SongRequest::where('reservation_id','=',$id)->delete();
        Payment::where('id','=',$reservation['payment_id'])->delete();
        Reservation::where('id','=',$id)->delete();

        return response([
            'message'=>'The reservation has been deleted successfully'
        ]);
    }

}
