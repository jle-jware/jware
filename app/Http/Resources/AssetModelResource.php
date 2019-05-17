<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AssetModelResource extends Resource
{
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'company_id' => $this->company_id,
            'company' => $this->company,
            'name' => $this->name,
            'model_type_id' => $this->model_type_id,
            'modelType' => $this->modelType,
            'rent' => $this->rent,
            'frequency' => $this->frequency,
            'value' => $this->value,
            'unit' => $this->unit,
            'minimum_stock' => $this->minimum_stock,
            'current_stock' => $this->current_stock,
            'utilization_type' => $this->utilization_type,
            'cannister_no' => $this->cannister_no,
            'drink_no' => $this->drink_no,
            'image' => $this->image,
            'description' => $this->description,
            'comment' => $this->comment,
            'machine_option' => $this->machine_option,
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
