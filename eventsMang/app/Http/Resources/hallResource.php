<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class hallResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'user_id'=> $this->user_id,
            'location_coordinates_id'=> $this->location_coordinates_id,
            'work_time_id'=> $this->work_time_id,
            'hall_capacity_id'=> $this->hall_capacity_id,
            'rating_id'=> $this->rating_id,
            'hall_type_id'=> $this->hall_type_id,
            'name'=> $this->name,
            'has_recordrd'=> $this->has_recordrd,
            'space'=> $this->space,
            'price_per_hour'=> $this->price_per_hour,
            'license_image'=> $this->license_image,
            'panorama_image'=> $this->panorama_image,
            'external_image'=> $this->external_image,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this -> created_at->format('Y-m-d'),
            'favorite' => count(Favorite::where('user_id', Auth::user()->id)->where('hall_id', $this->id)->get()) == 0 ? 0 : 1 ,
        ];
    }
}
