<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class JobResource extends Resource
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
            'account' => $this->account,
            'user_id' => $this->user_id,
            'user' => $this->user,
            'contractor_id' => $this->contractor_id,
            'company_id' => $this->company_id,
            'job_number' => $this->job_number,
            'inspection_description_id' => $this->inspection_description_id,
            'company' => $this->company,
            'company_name' => $this->company ? $this->company->name: 'Not Set',
            'asset_id' => $this->asset_id,
            'asset' => $this->asset,
            'asset_name' => $this->asset ? $this->asset->name: 'Not Set',
            'asset_serial' => $this->asset ? $this->asset->serial: 'Not Set',
            'asset_bmb' => $this->asset ? $this->asset->bmb: 'Not Set',
            'asset_specific_location' => $this->asset ? $this->asset->specific_location: 'Not Set',
            'job_type_id' => $this->job_type_id,
            'job_type_name' => $this->jobType ? $this->jobType->name: 'Not Set',
            'jobType' => $this->jobType,
            'cost_labour' => $this->cost_labour,
            'location' => $this->location,
            'material_labour' => $this->material_labour,
            'hours' => $this->hours,
            'machine_option' => $this->machine_option,
            'asset_model_id' => $this->asset_model_id,
            'email_sent' => $this->email_sent,
            'job_stat_id' => $this->job_stat_id,
            'jobStat' => $this->jobStat,
            'job_status' => $this->jobStat ? $this->jobStat->type: 'Not Set',
            'job_raised_date' => Carbon::parse($this->job_raised_date)->toW3cString(),
            'required_date' => Carbon::parse($this->required_date)->toW3cString(),
            'comment' => $this->comment,
            'description' => $this->description,
            'has_optional_contact' => $this->has_optional_contact,
            'contact_person' => $this->contact_person,
            'contact_number' => $this->contact_number,
            'priority_id' => $this->priority_id,
            'priority' => $this->priority,
            'job_priority' => $this->priority ? $this->priority->name: 'Not Set',
            'inspection_id' => $this->inspection_id,
            'admin_close_out' => $this->admin_close_out,
            'technical_manager_close_out' => $this->technical_manager_close_out,
            'review' => $this->review,
            'send_email' => $this->send_email,
            'close_by_admin' => $this->close_by_admin,
            'is_nuisance' => $this->is_nuisance,
            'need_approval' => $this->need_approval,
            'charge_to' => $this->charge_to,
            'raised_by' => $this->raised_by,
            'created_by'=>$this->created_by,
            'updated_by'=>$this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'contractor' => $this->contractor  ? $this->contractor->name: ' ',
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
