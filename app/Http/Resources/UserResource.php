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
            'user_name' => $this->userName,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
             'role_name'=>$this->role_name,
             'profile_image'=>$this->profile_image
        ];



    }

    }

