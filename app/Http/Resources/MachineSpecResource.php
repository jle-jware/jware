<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MachineSpecResource extends Resource
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
            'asset_model_id' => $this->asset_model_id,
            'serial'=> $this->serial,
            'bmb' => $this->bmb,
            'is_used' => $this->is_used == 1? true : false,
            'asset_model_name' => $this->assetModel ? $this->assetModel->name :'Not Set',
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
