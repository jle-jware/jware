<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CompanyTypeResource extends Resource
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
            'type' => $this->type,
            'comment' => $this->comment,
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
