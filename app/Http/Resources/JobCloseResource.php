<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class JobCloseResource extends Resource
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
            'user_id' => $this->user_id,
            'job_id' => $this->job_id,
            'is_complete' => $this->is_complete,
            'send_email' => $this->send_email,
            'review' => $this->review,
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
