<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;
class AssetDatatableResource extends Resource
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
            'company_id' => $this->company_id,
            'agent_id' => $this->agent_id,
            'location_id' => $this->location_id,
            'name' => $this->name,
            'date' => Carbon::parse($this->date)->format('d-m-y'),
            'company_name' => $this->company ? $this->company->name: 'Not Set',
            'asset_model_id' => $this->asset_model_id,
            'asset_model_name' => $this->assetModel ? $this->assetModel->name: 'Not Set',
            'location_description' => $this->location ? $this->location->description: 'Not Set',
            'location_address' => $this->location ? $this->location->address: 'Not Set',
            'distributor' => $this->location ? $this->location->agent_id: 'Not Set',
            'specific_location' => $this->specific_location ? $this->specific_location : 'Not Set',
            'serial' => $this->serial ? $this->serial: 'Not Set',
            'bmb' => $this->bmb ? $this->bmb: 'Not Set',
            'cost' => $this->cost,
            'asset_stat_id' => $this->asset_stat_id,
            'asset_status_name' => $this->assetStat ? $this->assetStat->type: 'Not Set',
            'description' => $this->description,
            'comment' => $this->comment,
            'created_by' => $this->created_by,
            'created_name' => $this->createdUser ? $this->createdUser->first_name.' '.$this->createdUser->last_name: 'Not Set',
            'updated_name' => $this->updatedUser ? $this->updatedUser->first_name.' '.$this->updatedUser->last_name: 'Not Set',
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at->format('d-m-Y H:i:s A'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s A')
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
