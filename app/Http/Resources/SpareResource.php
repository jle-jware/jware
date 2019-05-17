<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SpareResource extends Resource
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
            'asset_model_id' => $this->asset_model_id,
            'assetModel' => $this->assetModel,
            'spare_type_id' => $this->spare_type_id,
            'spare_type' => $this->spare_type,
            'asset_id' => $this->asset_id,
            'name' => $this->name,
            'serial' => $this->serial,
            'cost' => $this->cost,
            'status' => $this->status,
            'minimum_stock' => $this->minimum_stock,
            'current_stock' => $this->current_stock,
            'image' => $this->image,
            'description' => $this->description,
            'comment' => $this->comment,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'full_spare_info' => $this->name. '-'.$this->serial
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
