<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SpareJobResource extends Resource
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
            'spare_id' => $this->spare_id,
            'job_id' => $this->job_id,
            'spare_name' => $this->spare ? $this->spare->name :'Not Set',
            'allocated' => $this->allocated,
            'used' => $this->used,
            'comment' => $this->comment,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
