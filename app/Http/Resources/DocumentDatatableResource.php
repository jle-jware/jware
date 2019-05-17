<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DocumentDatatableResource extends Resource
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
            'asset_id'=>$this->asset_model_id,
            'file' => $this->file,
            'document_type_id' => $this->document_type_id,
            'documentType' => $this->documentType,
            'url' => $this->url,
            'comment' => $this->comment,
            'description' => $this->description,
            'created_by'=>$this->created_by,
            'updated_by'=>$this->updated_by,
            'document_type' => $this->documentType ? $this->documentType->type : 'Not Set',
            'asset_name' => $this->assetModel ? $this->assetModel->name : 'Not Set',
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
