<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class JobSheetDatatableResource extends JsonResource
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
            'job_number' => $this->job  ? $this->job->job_number: 'Not Set',
            'job_type' => $this->job ? ($this->job->jobType ? $this->job->jobType->name : 'Not Set'): 'Not Set',
            'asset' => $this->job ? ($this->job->asset ? $this->job->asset->name : 'Not Set'): 'Not Set',
            'serial' => $this->job ? ($this->job->asset ? $this->job->asset->serial : 'Not Set'): 'Not Set',
            'date' => Carbon::parse($this->job_date)->format('d-m-y H:i A'),
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
            'run_flush' => $this->run_flush == 1? 'Yes':'No',
            'work_area' => $this->work_area == 1? 'Yes':'No',
            'product_checked' => $this->product_checked == 1? 'Yes':'No',
            'is_complete' => $this->is_complete == 1? 'Yes':'No',
            'second_trip' => $this->second_trip == 1? 'Yes':'No',
            'customer_signature' => $this->customer_signature,
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
