<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetInspectionResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'asset_id' => $this->asset_id,
            'description' => $this->description ? $this->description: 'Not Set' ,
            'frequency' => $this->frequency ? $this->frequency: 'Not Set' ,
            'group' => $this->group ? $this->group :'Not Set' ,
            'inspection_criteria_id' => $this->inspection_criteria_id,
            'inspection_criteria' => $this->inspectionCriteria ? $this->inspectionCriteria->name : 'Not Set',
            'file' => $this->file ? $this->file: 'No Files Uploaded' ,
            'comment' => $this->comment ? $this->comment: 'Not Set' ,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
