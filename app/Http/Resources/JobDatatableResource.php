<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;
class JobDatatableResource extends Resource
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
            // 'account_id' => $this->account_id,
            // 'user_id' => $this->user_id,
            //'contractor_id' => $this->contractor_id,
            //'company_id' => $this->company_id,
            'job_number' => $this->job_number,
            'location_address' => $this->location ? $this->location->description . ',' . $this->location->address. ',' . $this->location->agent_id: 'Not Set',
            'location_description' => $this->location ? $this->location->description: 'Not Set',
            'asset_model' => $this->asset ? ($this->asset->assetModel ? $this->asset->assetModel->name : 'Not Set'): 'Not Set',
            'placement_asset_model' => $this->assetModel  ? $this->assetModel->name: 'Not Set',
            //'asset_id' => $this->asset_id,
            //'job_type_id' => $this->job_type_id,
            'cost_labour' => $this->cost_labour,
            'material_labour' => $this->material_labour,
            'hours' => $this->hours,
            //'job_stat_id' => $this->job_stat_id,
            'job_raised_date' => Carbon::parse($this->job_raised_date)->format('d-m-y H:i A'),
            'required_date' => $this->required_date,
            'comment' => $this->comment,
            'description' => $this->description,
            'has_optional_contact' => $this->has_optional_contact,
            'contact_person' => $this->contact_person,
            'contact_number' => $this->contact_number,
            //'priority_id' => $this->priority_id,
            //'inspection_id' => $this->inspection_id,
            //'admin_close_out' => $this->admin_close_out,
            //'technical_manager_close_out' => $this->technical_manager_close_out,
            //'review' => $this->review,
            //'send_email' => $this->send_email,
            //'close_by_admin' => $this->close_by_admin,
            //'closed_by_manager' => $this->closed_by_manager,
            //'raised_by' => $this->raised_by,
            //'created_by'=>$this->created_by,
            //'updated_by'=>$this->updated_by,
            'tech_name' => $this->user ? $this->user->last_name. ' , '.$this->user->first_name : 'Not Set',
            'job_type_name' => $this->jobType ? $this->jobType->name: 'Not Set',
            'customer_name' => $this->company ? $this->company->name: 'Not Set',
            'job_status' => $this->jobStat ? $this->jobStat->type: 'Not Set',
            'job_priority' => $this->priority ? $this->priority->name: 'Not Set',
            'asset_name' => $this->asset ? $this->asset->name: 'Not Set',
            'asset_serial' => $this->asset ? $this->asset->serial: 'Not Set',
            'asset_bmb' => $this->asset ? $this->asset->bmb: 'Not Set',
            'created_at' => $this->created_at->format('d-m-Y H:i:s A'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s A'),
            'created_name' => $this->createdUser ? $this->createdUser->first_name.' '.$this->createdUser->last_name: 'Not Set',
            'updated_name' => $this->updatedUser ? $this->updatedUser->first_name.' '.$this->updatedUser->last_name: 'Not Set',
            'contractor' => $this->contractor  ? $this->contractor->name: ' ',
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
