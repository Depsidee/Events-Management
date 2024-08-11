<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;
use App\Models\Hall;
use App\Models\Report;
use App\Models\Reservation;

class ReportController extends BaseController
{
    public function indexReports()
    {
        if(!(Auth::user()->role_name=='super_admin'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }

        $reports = Report::with('user','hall')->get();
        if($reports->count()<1)
        {
            return response([
                'message'=>'There is no reports yet.'
            ]);
        }

        return $reports;
    }

    public function addReport(Request $request)
    {
        $request->validate([
            'hall_id'=>'required|integer',
            'body'=>'required|string'
        ]);

        if(!(Auth::user()->role_name=='client'))
        {
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }
        $user_id = auth()->user()->id;

        $hall_id = $request->hall_id;
        $hall = Hall::find($hall_id);
        if($hall==null)
        {
            return response([
                'message'=>'There is no hall with such id.'
            ],404);
        }

        $now = date("Y-m-d");
        $reservations = Reservation::
                        where('user_id','=',$user_id)
                        ->where('hall_id','=',$hall_id)
                        ->where('date','<',$now)
                        ->get();
        if($reservations->count()<1)
        {
            return response([
                'message'=>"You Can't submit a report on a hall you didn't reserve."
            ]);
        }

        $report = Report::create([
            'user_id'=>$user_id,
            'hall_id'=>$hall_id,
            'body'=>$request->body
        ]);
        return response([
            'message'=>'Your report has been submitted successfully.',
            'report'=>$report
        ]);
    }
}
