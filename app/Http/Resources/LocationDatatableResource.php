<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class LocationDatatableResource extends Resource
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
            // 'user' => $this->user,
            'user_id'=> $this->user_id,
            'region_id'=> $this->region ? $this->region_id : 'Not Set',
            'company_id' => $this->company_id,
            'address' => $this->address,
            'is_primary' => $this->is_primary == 1? 'Yes':'No',
            'description' => $this->description,
            'phone_number' => $this->phone_number,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'full_name' => $this->user ? $this->user->first_name. ' '.$this->user->last_name : 'Not Set',
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
