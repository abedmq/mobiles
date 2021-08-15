<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MobileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'is_found' => @$this->id ? true : false,
            'mobile' => @$this->mobile ?: '',
            'country_code' => @$this->country_code ?: '',
            'name' => @$this->full_name ?: (($this->first_name??'')." ".($this->last_name??'')),
            'gender' => @$this->gender ?: '',
            'social_status' => @$this->social_status ?: '',
            'facebook_url' => @$this->facebook_url ?: '',
            'location' => @$this->location ?: '',
            'email' => @$this->email ?: '',
        ];
    }
}
