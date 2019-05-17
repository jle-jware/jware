<?php

namespace App\Http\Resources;
use Carbon\Carbon;

use Illuminate\Http\Resources\Json\Resource;

class GalleryResource extends Resource
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
            'description' => $this->description,
            'date' => Carbon::parse($this->date)->toW3cString(),
            'image' => $this->image,
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
