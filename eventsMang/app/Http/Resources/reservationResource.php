<?php

namespace App\Http\Resources;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


        public function toArray(Request $request): array
    {

        return[
            'id' =>$this->id,
            'user_id' => $this->user_id,
            'hall_id' => $this->hall_id,
            'decoration_id' => $this->decoration_id,
             '  '=>$this->payment_id,
             'photography_id'=>$this->photography_id,
             'reservation_type_id'=>$this->reservation_type_id,
             'has_recorded'=>$this->has_recorded,
             'transportation'=>$this->transportation,
             'period'=>$this->period,
             'start_time'=>$this->start_time,
             'total_price'=>$this->total_price,
             'children_permission'=>$this->children_permission,
             'guest_photography'=>$this->guest_photography,
             'date'=>$this->date->format('Y-m-d'),
             'delete_time'=>$this->delete_time->format('Y-m-d'),
             'created_at' => $this->created_at->format('Y-m-d')
        ];


    }

    }

