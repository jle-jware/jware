<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class JobSheetResource extends Resource
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
            'swmc' => $this->swmc ? $this->swmc:'Not Set',
            'job_date' => $this->job_date,
            'timein' => $this->timein ? $this->timein:'Not Set',
            'timeout' => $this->timeout ? $this->timeout:'Not Set',
            'onstie' => $this->onstie ? $this->onstie:'Not Set',
            'travel_hours' => $this->travel_hours ? $this->travel_hours: 'Not Set',
            'total_kms' => $this->total_kms ? $this->total_kms:'Not Set',
            'waiting_time' => $this->waiting_time ? $this->waiting_time: 'Not Set',
            'waiting_reason' => $this->waiting_reason ? $this->waiting_reason: 'Not Set',
            'comment' => $this->comment ? $this->comment:'Not Set',
            'run_flush' => $this->run_flush,
            'work_area' => $this->work_area,
            'product_checked' => $this->product_checked,
            'is_complete' => $this->is_complete,
            'second_trip' => $this->second_trip,
            'image' => $this->image,
            'customer_signature' => $this->customer_signature,
            'created_by'=>$this->created_by,
            'updated_by'=>$this->updated_by,
            'created_name' => $this->createdUser ? $this->createdUser->first_name.' '.$this->createdUser->last_name: 'Not Set',
            'updated_name' => $this->updatedUser ? $this->updatedUser->first_name.' '.$this->updatedUser->last_name: 'Not Set'
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
