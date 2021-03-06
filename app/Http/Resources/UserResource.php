<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
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
            'account' => $this->account,
            'account_id' => $this->account_id,
            'company' => $this->company,
            'company_id'=>$this->company_id,
            'location_id'=>$this->location_id,
            'location'=>$this->location,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'phone_number' => $this->phone_number,
            'is_user' => $this->is_user,
            'key_contact' => $this->key_contact,
            'contact_type' => $this->contact_type,
            'image' => $this->image,
            'created_by'=>$this->created_by,
            'updated_by'=>$this->updated_by,
            'full_name' => $this->last_name. ' , '.$this->first_name
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
