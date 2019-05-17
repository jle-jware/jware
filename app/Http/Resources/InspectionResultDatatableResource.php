<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InspectionResultDatatableResource extends JsonResource
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
            'job_id' => $this->job_id,
            'job_description' => $this->job ? $this->job->description:'Not Set', 
            'result_id' => $this->result_id,
            'result_name' => $this->result ? $this->result->name: 'Not Set',
            'status' => $this->status,
            'file' => $this->file,
            'band' => $this->band,
            'insulation' => $this->insulation,
            'leakage' => $this->leakage,
            'date' => Carbon::parse($this->date)->format('d-m-Y'),
            'next_job_date' => Carbon::parse($this->date)->addDays($this->job->asset->assetInspection->frequency)->format('d-m-Y'),
            'comment' => $this->comment ? $this->comment:'Not Set' ,
            'created_by'=>$this->created_by,
            'updated_by'=>$this->updated_by,
            'asset_name' => $this->job ? ($this->job->asset ? $this->job->asset->name : 'Not Set'): 'Not Set',
            'location' => $this->job ? ($this->job->location ? $this->job->location->description.'-'.$this->job->location->address : 'Not Set'): 'Not Set',
            'company_name' => $this->job ? ($this->job->company ? $this->job->company->name : 'Not Set'): 'Not Set',
            'serial' => $this->job ? ($this->job->asset ? $this->job->asset->serial : 'Not Set'): 'Not Set',
            'bmb' => $this->job ? ($this->job->asset ? $this->job->asset->bmb : 'Not Set'): 'Not Set',
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
