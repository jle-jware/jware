<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DocumentResource extends Resource
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
            'account' => $this->account,
            'account_id' => $this->account_id,
            'assetModel' => $this->assetModel,
            'asset_model_id'=>$this->asset_model_id,
            'file' => $this->file,
            'comment' => $this->comment,
            'description' => $this->description,
            'document_type_id' => $this->document_type_id,
            'documentType' => $this->documentType,
            'url' => $this->url,
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
