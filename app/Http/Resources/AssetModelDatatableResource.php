<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AssetModelDatatableResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
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
            'unit' => $this->unit,
            'minimum_stock' => $this->minimum_stock,
            'current_stock' => $this->current_stock,
            'image' => $this->image,
            'description' => $this->description,
            'comment' => $this->comment,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'company_name' => $this->company ? $this->company->name : 'Not Set',
            'model_type' => $this->modelType ? $this->modelType->type : 'Not Set',
            'asset_address' => $this->location ? $this->location->address :'Not Set',
            'asset_place' => $this->location ? $this->location->description :'Not Set',
            'cannister_no' => $this->cannister_no,
            'drink_no' => $this->drink_no,
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
