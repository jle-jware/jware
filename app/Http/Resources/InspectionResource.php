<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class InspectionResource extends Resource
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
            'group' => $this->group,
            'inspection_criteria_id' => $this->inspection_criteria_id,
            'frequency' => $this->frequency,
            'description' => $this->description,
            'file' => $this->file,
            'comment' => $this->comment,
            'inspection_criteria' => $this->inspectionCriteria ? $this->inspectionCriteria->name : 'Not Set',
            'created_by'=>$this->created_by,
            'updated_by'=>$this->updated_by,
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
