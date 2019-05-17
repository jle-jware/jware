<?php

namespace App\Http\Resources;
use Carbon\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetUtilizationDatatableResource extends JsonResource
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
            'full_date' =>Carbon::parse($this->date)->format('d-m-Y'),
            'date' =>$this->date,
            'utilization_type_id' => $this->utilization_type_id,
            'utilization_type' => $this->utilizationType ? $this->utilizationType->utilization_type :'Not Set',
            'utilization_unit' => $this->utilizationType ? $this->utilizationType->utilization_unit.' '.$this->utilizationType->rate_unit :'Not Set',
            'rate_unit' => $this->utilizationType ? $this->utilizationType->rate_unit :'Not Set',
            'value' => $this->value,
            'rate' => $this->rate,
            'clear_calculation' => $this->clear_calculation,
            'clear_calculation_description' => $this->clear_calculation == 1? 'Yes':'No',
            'parts_changed' => $this->parts_changed == 1? 'Yes':'No',
            'comment' => $this->comment,
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
