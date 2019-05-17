<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;
class AssetResource extends Resource
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
            'company_id' => $this->company_id,
            'company' => $this->company,
            'agent_id' => $this->agent_id,
            'location_id' => $this->location_id,
            'location' => $this->location,
            'date' => Carbon::parse($this->date)->toW3cString(),
            'full_name' => $this->location ? ($this->location->user ? $this->location->user->last_name . ' , ' . $this->location->user->first_name :'Not Set'): 'Not Set',
            'email' => $this->location ? ($this->location->user ? $this->location->user->email : 'Not Set'): 'Not Set',
            'name' => $this->name,
            'asset_model_id' => $this->asset_model_id,
            'assetModel' => $this->assetModel,
            'specific_location' => $this->specific_location,
            'serial' => $this->serial,
            'bmb' => $this->bmb,
            'cost' => $this->cost,
            'asset_stat_id' => $this->asset_stat_id,
            'description' => $this->description,
            'machine_option' => $this->machine_option,
            'is_charged' => $this->is_charged,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'custom' => $this->name . ' ' . $this->serial . ' ' . $this->bmb,
            'asset_serial' => $this->serial,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
