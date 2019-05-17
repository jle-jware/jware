<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MilestoneDatatableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'company_id' => $this->company_id,
            'asset_id'=> $this->asset_id,
            'asset' => $this->asset,
            'job_id' =>$this->job_id,
            'milestone_type_id' => $this->milestone_type_id,
            'milestoneType' => $this->milestoneType,
            'description' => $this->description,
            'cup_count' => $this->cup_count,
            'cup_count_per_day' => $this->cup_count_per_day,
            'test_tag' => $this->test_tag,
            'date' => $this->date,
            'comment' => $this->comment,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'milestone_type_name' => $this->milestoneType ? $this->milestoneType->name :'Not Set',
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
