<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class LocationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'user_id'=> $this->user ? $this->user_id : '',
            'region_id'=> $this->region ? $this->region_id : '',
            'company_id' => $this->company_id,
            'address' => $this->address,
            'is_primary' => $this->is_primary,
            'description' => $this->description,
            'agent_id' => $this->agent_id,
            'phone_number' => $this->phone_number,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'full_address' =>$this->description.' - '.$this->address.' - '.$this->agent_id,
            'user_name' => $this->user ? $this->user->last_name.' '.$this->user->first_name :'',
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
