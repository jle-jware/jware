<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MachineOptionResource extends Resource
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
            'option_name'=> $this->option_name,
            'value' => $this->value,
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
