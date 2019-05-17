<?php

namespace App\Http\Resources;
use Carbon\Carbon;

use Illuminate\Http\Resources\Json\Resource;

class AssetUtilizationResource extends Resource
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
            'asset_id' => $this->asset_id,
            'job_id' => $this->job_id,
            'date' => Carbon::parse($this->date)->toW3cString(),
            'utilization_type_id' => $this->utilization_type_id,
            'value' => $this->value,
            'clear_calculation' => $this->clear_calculation,
            'parts_changed' => $this->parts_changed,
            'rate' => $this->rate,
            'comment' => $this->comment,
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
